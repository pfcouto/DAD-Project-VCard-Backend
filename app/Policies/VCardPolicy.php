<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VCard;
use Illuminate\Auth\Access\HandlesAuthorization;

class VCardPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->user_type == 'A';
    }

    public function view(User $user, VCard $vcard)
    {
        return $user->user_type == 'A' || $user->id == $vcard->phone_number;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, VCard $vcard)
    {
        return $user->user_type == 'A' || $user->id == $vcard->phone_number;
    }

    public function updatePassword(User $user, VCard $vcard)
    {
        return $user->id == $vcard->phone_number;
    }

    public function updatePhoto(User $user, VCard $vcard)
    {
        return $user->id == $vcard->phone_number;
    }

    public function updateCode(User $user, VCard $vcard)
    {
        return $user->id == $vcard->phone_number;
    }

    public function updateBlock(User $user, VCard $vcard)
    {
        return $user->user_type == 'A';
    }

    public function delete(User $user, VCard $vcard)
    {
        return $user->id == $vcard->phone_number;
    }

    public function viewCategoriesOfVCard(User $user, VCard $vcard)
    {
        if ($user->user_type == 'V' && $user->username == $vcard->phone_number) {
            return true;
        }
        return false;
    }
    
    public function viewTransactionsOfVCard(User $user, VCard $vCard)
    {
        return $user->username == $vCard->phone_number;
    }
    
    public function viewContactsOfVCard(User $user, VCard $vCard)
    {
        return $user->username == $vCard->phone_number;
    }
}
