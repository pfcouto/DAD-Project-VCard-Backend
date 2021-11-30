<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        dd($user->user_type);
        return ($user->user_type == "V");
    }

    public function view(User $user, User $model)
    {
        dd($user->user_type);

        return $user->user_type == "V" || $user->username == $model->username;
    }

    public function create(User $user)
    {
        dd($user->user_type);

        return ($user->user_type == "V");
    }

    public function update(User $user, User $model)
    {
        dd($user->user_type);

        return $user->type == "V" || $user->username == $model->username;
    }

    public function destroy(User $user, User $model)
    {
        dd($user->user_type);

        return $user->type == "V" || $user->username == $model->username;
    }
}
