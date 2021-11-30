<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DefaultCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        // dd($user->user_type);
        // return ($user->user_type == "A");
        return true;
    }

    public function view(User $user, User $model)
    {
        dd($user->user_type);
        return $user->user_type == "A" || $user->username == $model->username;
    }

    public function create(User $user)
    {
        dd($user->user_type);

        return ($user->user_type == "A");
    }

    public function update(User $user, User $model)
    {
        dd($user->user_type);

        return $user->type == "A" || $user->username == $model->username;
    }

    public function destroy(User $user, User $model)
    {
        dd($user->user_type);

        return $user->type == "A" || $user->username == $model->username;
    }
}
