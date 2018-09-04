<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VenuePolicy
{
    use HandlesAuthorization;

    /**
     * Checks to see if the user has permission to view list of venues.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function index(User $user)
    {
        return $user->hasPermission('view-venues');
    }

    /**
     * Checks to see if the user has permission to create a venue.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('create-venue');
    }

    /**
     * Checks to see if the user has permission to view a venue.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function show(User $user)
    {
        return $user->hasPermission('show-venue');
    }

    /**
     * Checks to see if the user has permission to edit a venue.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function edit(User $user)
    {
        return $user->hasPermission('edit-venue');
    }

    /**
     * Checks to see if the user has permission to update a venue.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->hasPermission('update-venue');
    }

    /**
     * Checks to see if the user has permission to delete a venue.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->hasPermission('delete-venue');
    }
}
