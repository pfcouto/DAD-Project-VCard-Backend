<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\VCardResource;
use App\Models\VCard;
use App\Http\Requests\UpdateVCardRequest;
use App\Http\Requests\StoreVCardRequest;
use App\Http\Requests\UpdateVCardBlockedRequest;
use App\Http\Requests\UpdateVCardPasswordRequest;
use App\Http\Requests\UpdateVCardCodeRequest;


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
        $vCardUser = VCard::findOrFail($request->username);
        return new VCardResource($vCardUser);
    }

    public function store(StoreVCardRequest $request)
    {
        $newVCard = $request->validated();
        $newVCard['balance'] = 0;
        $newVCard['blocked'] = 0;
        $newVCard['max_debit'] = 5000;
        $newVCard['password'] = bcrypt($newVCard['password']);
        $newVCard['confirmation_code'] = bcrypt($newVCard['confirmation_code']);

        $createdVCard = VCard::create($newVCard);

        return new VCardResource($createdVCard);
    }

    public function update(UpdateVCardRequest $request, VCard $vcard)
    {
        $vcard->update($request->validated());
        return new VCardResource($vcard);
    }

    public function update_code(UpdateVCardCodeRequest $request, VCard $vcard)
    {
        $vcard->confirmation_code = bcrypt($request->validated()['code']);
        $vcard->save();
        return new VCardResource($vcard);
    }

    public function update_password(UpdateVCardPasswordRequest $request, VCard $vcard)
    {
        $vcard->password = bcrypt($request->validated()['password']);
        $vcard->save();
        return new VCardResource($vcard);
    }

    public function update_blocked(UpdateVCardBlockedRequest $request, VCard $vcard)
    {
        $vcard->blocked = $request->validated()['blocked'];
        $vcard->save();
        return new VCardResource($vcard);
    }

    public function destroy($vcard)
    {
        $vcard = VCard::withTrashed()->findOrFail($vcard);

        if ($vcard->balance > 0) return response('Trying to delete a vcard that has a positive Balance', 500);

        if ($vcard->transactions->count() > 0) {
            //soft delete
            $vcard->delete();
            return new VCardResource($vcard);
        }
        $vcard->forceDelete();
        return new VCardResource($vcard);
    }
}
