<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Models\VCard;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return TransactionResource::collection(Transaction::all());
    }

    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    public function getTransactionsOfVCard(Request $request, VCard $vcard)
    {
        $category = $request->category ?? '';
        $order = $request->order ?? '';
        $orderBy = $request->orderBy ?? '';
        $type = $request->type ?? '';
        $from = $request->from ?? '';
        $to = $request->to ?? '';

        $qry = Transaction::query()->where('vcard', $vcard->phone_number);

        if ($category) {
            $qry->where('category_id', $category);
        }

        if ($type) {
            $qry->where('type', $type);
        }

        if ($from) {
            $qry->where('datetime', '>', $from);
        }

        if ($to) {
            $qry->where('datetime', '<', $to);
        }

        $orderByFinal = $orderBy == 'value' ? 'value' : 'datetime';
        switch ($order) {
            case 'asc':
                $qry->orderBy($orderByFinal, 'asc');
                break;
            case 'desc':
                $qry->orderBy($orderByFinal, 'desc');
                break;
        }

        return TransactionResource::collection($qry->paginate(10));
    }

    public function store(StoreTransactionRequest $request)
    {
        $validated_data = $request->validated();

        $vCard = VCard::where('phone_number', $validated_data['vcard'])->first();

        if ($validated_data['type'] == 'D') {
            $balanceValidator = Validator::make(
                $validated_data,
                ['value' => [function ($attribute, $value, $fail) use ($vCard) {
                    if ($value > $vCard->balance) {
                        $fail('Value inserted in transaction (' . $value . '€) is greater than the available balance');
                    } elseif ($value > $vCard->max_debit) {
                        $fail('Value inserted in transaction (' . $value . '€) is greater than the maximum debit amount');
                    }
                }]]
            );
            $balanceValidator->validate();
        }

        if ($validated_data['payment_type'] == 'VCARD') {
            $paymentReferenceValidator = Validator::make(
                $validated_data,
                ['payment_reference' => [function ($attribute, $value, $fail) use ($validated_data) {
                    if (!VCard::find($value)) {
                        $fail('This VCard doesn\'t exist');
                    } else if ($value == $validated_data["vcard"]) {
                        $fail('Cannot Debit to the your own vcard');
                    }
                }]]
            );
            $paymentReferenceValidator->validate();
        }


        $generalPaymentReferenceValidator = Validator::make(
            $validated_data,
            ['payment_reference' => [function ($attribute, $value, $fail) use ($validated_data) {
                switch ($validated_data["payment_type"]) {
                    case 'MBWAY':
                    case 'VCARD':
                        if (!preg_match('/^9[0-9]{8}$/', $value)) {
                            $fail('Invalid phone number format');
                        }
                        break;
                    case 'MB':
                        if (!preg_match('/^[0-9]{5}-[0-9]{9}$/', $value)) {
                            $fail('Invalid MB format');
                        }
                        break;
                    case 'IBAN':
                        if (!preg_match('/^PT50[0-9]{21}$/', $value)) {
                            $fail('Invalid IBAN format');
                        }
                        break;
                    case 'VISA':
                        if (!preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $value)) {
                            $fail('Invalid VISA format');
                        }
                        break;
                    case 'MASTERCARD':
                        if (!preg_match('/^5[1-5][0-9]{14}|^(222[1-9]|22[3-9]\\d|2[3-6]\\d{2}|27[0-1]\\d|2720)[0-9]{12}$/', $value)) {
                            $fail('Invalid MASTERCARD format');
                        }
                        break;
                    case 'PAYPAL':
                        if (!preg_match('/^(.+)@(.+){2,}\.(.+){2,}$/', $value)) {
                            $fail('Invalid PAYPAL format');
                        }
                }
            }]]
        );
        $generalPaymentReferenceValidator->validate();

        if (array_key_exists('category_id', $validated_data)) {
            $categoryValidator = Validator::make(
                $validated_data,
                ['category_id' => [function ($attribute, $value, $fail) use ($validated_data) {
                    if (!Category::where('id', $value)->where('vcard', $validated_data['vcard'])->first()) {
                        $fail('Invalid category');
                    }
                }]]
            );
            $categoryValidator->validate();
        }

        if ($validated_data['type'] == 'D') {
            $confirmationCodeValidator = Validator::make(
                $validated_data,
                ['confirmation_code' => [function ($attribute, $value, $fail) use ($vCard) {
                    if (!Hash::check($value, $vCard->confirmation_code)) {
                        $fail('Invalid confirmation code');
                    }
                }]]
            );
            $confirmationCodeValidator->validate();
        }

        $transaction = new Transaction($validated_data);
        $date = date('Y-m-d');
        $dateTime = date('Y-m-d H:i:s');
        $multiplier = -1;

        if ($validated_data['type'] == 'C')
            $multiplier = 1;

        DB::beginTransaction();
        try {
            $transaction->date = $date;
            $transaction->datetime = $dateTime;
            $transaction->old_balance = $vCard->balance;

            $vCard->balance += $multiplier * $transaction->value;
            $vCard->save();

            $transaction->new_balance = $vCard->balance;
            $transaction->save();

            if ($validated_data['payment_type'] == 'VCARD') {
                $pairVCard = VCard::where('phone_number', $validated_data['payment_reference'])->first();
                $pairTransaction = new Transaction($validated_data);

                $pairTransaction->vcard = $pairVCard->phone_number;
                $pairTransaction->date = $date;
                $pairTransaction->datetime = $dateTime;
                $pairTransaction->old_balance = $pairVCard->balance;
                $pairTransaction->pair_transaction = $transaction->id;
                $pairTransaction->payment_reference = $vCard->phone_number;
                $pairTransaction->description = null;
                $pairTransaction->category_id = null;

                $pairVCard->balance += -1 * $multiplier * $transaction->value;
                $pairVCard->save();

                $pairTransaction->new_balance = $pairVCard->balance;
                $pairTransaction->pair_vcard = $vCard->phone_number;
                $pairTransaction->type = $validated_data['type'] == 'C' ? 'D' : 'C';
                $pairTransaction->save();
                $transaction->pair_transaction = $pairTransaction->id;
                $transaction->pair_vcard = $pairVCard->phone_number;
                $transaction->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return new TransactionResource($transaction);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction->update($request->validated());
        return new TransactionResource($transaction);
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->vcard->trashed()) {
            $transaction->delete();
            return new TransactionResource($transaction);
        } else {
            return response('Trying to delete a transaction that has an associated VCard', 500);
        }
    }
}
