<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StipulationPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->can('view-stipulations');
    }

    public function create(User $user)
    {
//        dd($user);
//        dd(\Gate::forUser($user));
        return $user->hasPermission('create-stipulation');
    }

    public function edit(User $user)
    {
        return $user->can('edit-stipulation');
    }

    public function show(User $user)
    {
        return $user->can('show-stipulation');
    }

    public function delete(User $user)
    {
        return $user->can('delete-stipulation');
    }
}
