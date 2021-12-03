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

    public function getTransactionsOfVCard(VCard $vcard)
    {
        return TransactionResource::collection(Transaction::where('vcard', $vcard->phone_number)->orderByDesc('datetime')->paginate(10));
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
                    } else if ($value == $validated_data["vcard"]){
                        $fail('Cannot Debit to the your own vcard');
                    }
                }]]
            );
            $paymentReferenceValidator->validate();
        }

        if (array_key_exists('category_id', $validated_data)) {
            $categoryValidator = Validator::make(
                $validated_data,
                ['category_id' => [function ($attribute, $value, $fail) use ($validated_data) {
                    if (!Category::where('id', $value)->where('vcard', $validated_data['vcard'])) {
                        $fail('Invalid category');
                    }
                }]]
            );
            $categoryValidator->validate();
        }

        $confirmationCodeValidator = Validator::make(
            $validated_data,
            ['confirmation_code' => [function ($attribute, $value, $fail) use ($vCard) {
                if (!Hash::check($value, $vCard->confirmation_code)){
                    $fail('Invalid confirmation code');
                }
            }]]
        );
        $confirmationCodeValidator->validate();

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
