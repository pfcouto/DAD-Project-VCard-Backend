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

    public function update(User $user, User $vcard)
    {
        return $user->user_type == 'A' || $user->id == $vcard->phone_number;
    }

    public function updatePassword(User $user, User $vcard)
    {
        return $user->id == $vcard->phone_number;
    }

    public function updateCode(User $user, User $vcard)
    {
        return $user->id == $vcard->phone_number;
    }

    public function updateBlock(User $user, User $vcard)
    {
        return $user->user_type == 'A';
    }

    public function delete(User $user, User $vcard)
    {
        return ($user->id == $vcard->phone_number);
    }
    public function viewCategoriesOfVCard(User $user, VCard $vcard)
    {
        if ($user->user_type == 'V' && $user->username == $vcard->phone_number) {
            return true;
        }
        return false;
    }
}
