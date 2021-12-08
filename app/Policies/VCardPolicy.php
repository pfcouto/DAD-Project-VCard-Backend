<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VCard;
use Illuminate\Auth\Access\HandlesAuthorization;

class VCardPolicy
{
    use HandlesAuthorization;

    public function viewCategoriesOfVCard(User $user, VCard $vcard)
    {
        if ($user->user_type == 'V' && $user->username == $vcard->phone_number) {
            return true;
        }
        return false;
    }
}
