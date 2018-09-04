<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WrestlerPolicy
{
    use HandlesAuthorization;

    /**
     * Checks to see if the user has permission to view list of wrestlers.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function index(User $user)
    {
        return $user->hasPermission('view-wrestlers');
    }

    /**
     * Checks to see if the user has permission to create a wrestler.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('create-wrestler');
    }

    /**
     * Checks to see if the user has permission to view a wrestler.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermission('view-wrestler');
    }

    /**
     * Checks to see if the user has permission to edit a wrestler.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function edit(User $user)
    {
        return $user->hasPermission('edit-wrestler');
    }

    /**
     * Checks to see if the user has permission to update a wrestler.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->hasPermission('update-wrestler');
    }

    /**
     * Checks to see if the user has permission to delete a wrestler.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->hasPermission('delete-wrestler');
    }

    /**
     * Checks to see if the user has permission to retire a wrestler.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function retire(User $user)
    {
        return $user->hasPermission('retire-wrestler');
    }
}
