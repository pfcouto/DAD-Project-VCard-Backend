<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Contact $contact)
    {

        return $user->user_type == "V" && $user->username == $contact->phone_number;
    }

    public function create(User $user)
    {
        return $user->user_type == "V";
    }

    public function update(User $user, Contact $contact)
    {

        return $user->user_type == "V"
            && $user->username == $contact->phone_number;
    }

    public function destroy(User $user, Contact $contact)
    {

        return $user->user_type == "V"
            && $user->username == $contact->phone_number;
    }
}
