<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DefaultCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return ($user->user_type == "A");
    }

    public function view(User $user, User $model)
    {
        return $user->user_type == "A" || $user->username == $model->username;
    }

    public function create(User $user)
    {
        return ($user->user_type == "A");
    }

    public function update(User $user, User $model)
    {
        return $user->type == "A" || $user->username == $model->username;
    }

    public function destroy(User $user, User $model)
    {
        return $user->type == "A" || $user->username == $model->username;
    }
}
