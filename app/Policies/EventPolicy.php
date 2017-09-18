<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->hasPermission('view-events');
    }

    public function create(User $user)
    {
        return $user->hasPermission('create-event');
    }

    public function edit(User $user)
    {
        return $user->hasPermission('edit-event');
    }

    public function show(User $user)
    {
        return $user->hasPermission('show-event');
    }

    public function delete(User $user)
    {
        return $user->hasPermission('delete-event');
    }
}
