<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MatchPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->hasPermission('create-match');
    }

    public function edit(User $user)
    {
        return $user->hasPermission('edit-match');
    }

    public function show(User $user)
    {
        return $user->hasPermission('show-match');
    }

    public function delete(User $user)
    {
        return $user->hasPermission('delete-match');
    }
}
