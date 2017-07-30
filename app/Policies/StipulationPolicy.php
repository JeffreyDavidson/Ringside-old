<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StipulationPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->can('create-stipulation');
    }
}
