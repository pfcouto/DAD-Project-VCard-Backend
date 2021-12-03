<?php

namespace App\Policies;

use App\Models\DefaultCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DefaultCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->user_type == "A";
    }

    public function view(User $user, DefaultCategory $defaultCategory)
    {
        return $user->user_type == "A";
    }

    public function create(User $user)
    {
        return $user->user_type == "A";
    }

    public function update(User $user, DefaultCategory $defaultCategory)
    {
        return $user->user_type == "A";
    }

    public function destroy(User $user, DefaultCategory $defaultCategory)
    {
        return $user->user_type == "A";
    }
}
