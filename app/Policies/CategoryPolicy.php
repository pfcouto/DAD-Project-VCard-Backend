<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return ($user->user_type == "V");
    }

    public function view(User $user, User $model)
    {
        return $user->user_type == "V" || $user->username == $model->username;
    }

    public function create(User $user)
    {
        return ($user->user_type == "V");
    }

    public function update(User $user, User $model)
    {
        return $user->type == "V" || $user->username == $model->username;
    }

    public function destroy(User $user, User $model)
    {
        return $user->type == "V" || $user->username == $model->username;
    }
}
