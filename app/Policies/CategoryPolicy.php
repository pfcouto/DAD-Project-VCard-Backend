<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use App\Models\Vcard;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewCategoriesOfVCard(VCard $vcard)
    {
        // dd($vcard->phone_number);
        // if ($vcard->phone_number) {
        return true;
        // }
        // return false;
    }

    public function view(User $user, Category $category)
    {

        return $user->user_type == "V" && $user->username == $category->vcard;
    }

    public function create(User $user)
    {
        return $user->user_type == "V";
    }

    public function update(User $user, Category $category)
    {

        return $user->user_type == "V"
            && $user->username == $category->vcard;
    }

    public function destroy(User $user, Category $category)
    {

        return $user->user_type == "V"
            && $user->username == $category->vcard;
    }
}
