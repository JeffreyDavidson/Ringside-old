<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StipulationPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->hasPermission('view-stipulations');
    }

    public function create(User $user)
    {
        return $user->hasPermission('create-stipulation');
    }

    public function edit(User $user)
    {
        return $user->hasPermission('edit-stipulation');
    }

    public function show(User $user)
    {
        return $user->hasPermission('show-stipulation');
    }

    public function delete(User $user)
    {
        return $user->hasPermission('delete-stipulation');
    }
}
