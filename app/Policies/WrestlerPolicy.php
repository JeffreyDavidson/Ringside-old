<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WrestlerPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->hasPermission('view-wrestlers');
    }

    public function create(User $user)
    {
        return $user->hasPermission('create-wrestler');
    }

    public function show(User $user)
    {
        return $user->hasPermission('show-wrestler');
    }

    public function edit(User $user)
    {
        return $user->hasPermission('edit-wrestler');
    }

    public function delete(User $user)
    {
        return $user->hasPermission('delete-wrestler');
    }

    public function retire(User $user)
    {
        return $user->hasPermission('retire-wrestler');
    }
}
