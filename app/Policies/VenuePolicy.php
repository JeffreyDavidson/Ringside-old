<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VenuePolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->hasPermission('view-venues');
    }

    public function create(User $user)
    {
        return $user->hasPermission('create-venue');
    }

    public function show(User $user)
    {
        return $user->hasPermission('show-venue');
    }

    public function edit(User $user)
    {
        return $user->hasPermission('edit-venue');
    }

    public function delete(User $user)
    {
        return $user->hasPermission('delete-venue');
    }
}
