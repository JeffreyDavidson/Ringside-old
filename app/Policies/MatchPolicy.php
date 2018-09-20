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
     * Checks to see if the user has permission to update a match.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->hasPermission('update-match');
    }

    /**
     * Checks to see if the user has permission to view a match.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermission('view-match');
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
