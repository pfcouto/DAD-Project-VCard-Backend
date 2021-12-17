<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use App\Models\VCard;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Transaction $transaction)
    {

        return $user->user_type == "V" && $user->username == $transaction->vcard;
    }

    public function create()
    {
        return true;
    }

    public function update(User $user, Transaction $transaction)
    {

        return $user->user_type == "V"
            && $user->username == $transaction->vcard;
    }

    public function destroy(User $user)
    {

        return $user->user_type == "A";
    }
}
