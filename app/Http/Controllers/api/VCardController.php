<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\VCardResource;
use App\Models\VCard;
use App\Http\Requests\StoreUpdateVCardRequest;
use App\Http\Requests\UpdateVCardPasswordRequest;
use App\Http\Resources\TransactionResource;

class VCardController extends Controller
{
    public function index()
    {
        return VCardResource::collection(VCard::all());
    }

    public function show(VCard $vcard)
    {
        return new VCardResource($vcard);
    }

    public function show_me(Request $request)
    {
        return new VCardResource($request->vcard());
    }

    public function vcardTransactions(Vcard $vcard)
    {
        return TransactionResource::collection($vcard->transactions);
    }

    public function store(StoreUpdateVCardRequest $request)
    {
        $newVCard = $request->validated();
        $newVCard['balance'] = 0;
        $newVCard['blocked'] = 0;
        $newVCard['max_debit'] = 5000;
        $newVCard['password'] = bcrypt($newVCard['password']);

        $createdVCard = VCard::create($newVCard);

        return new VCardResource($createdVCard);
    }

    public function update(StoreUpdateVCardRequest $request, VCard $vcard)
    {
        $vcard->update($request->validated());
        return new VCardResource($vcard);
    }

    public function update_password(UpdateVCardPasswordRequest $request, VCard $vcard)
    {
        $vcard->password = bcrypt($request->validated()['newPassword']);
        $vcard->save();
        return new VCardResource($vcard);
    }

    public function destroy($vcard)
    {
        $vcard = VCard::withTrashed()->findOrFail($vcard);

        if ($vcard->transactions->count() > 0) {
            //soft delete
            $vcard->delete();
            return new VCardResource($vcard);
        }
        $vcard->forceDelete();
        return new VCardResource($vcard);
    }
}
