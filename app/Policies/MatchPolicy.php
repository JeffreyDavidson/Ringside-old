<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MatchPolicy
{
    use HandlesAuthorization;

    /**
     * Checks to see if the user has permission to create a match.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('create-match');
    }

    /**
     * Checks to see if the user has permission to edit a match.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function edit(User $user)
    {
        return $user->hasPermission('edit-match');
    }

    /**
     * Checks to see if the user has permission to show a match.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function show(User $user)
    {
        return $user->hasPermission('show-match');
    }

    /**
     * Checks to see if the user has permission to delete a match.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->hasPermission('delete-match');
    }
}
