<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization;


    public function update(User $user, User $person)
    {
        return $user->id === $person->id;
    }
}
