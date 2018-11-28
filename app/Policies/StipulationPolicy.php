<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StipulationPolicy
{
    use HandlesAuthorization;

    /**
     * Checks to see if the user has permission to view list of stipulations.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function index(User $user)
    {
        return $user->hasPermission('view-stipulations');
    }

    /**
     * Checks to see if the user has permission to create a stipulation.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('create-stipulation');
    }

    /**
     * Checks to see if the user has permission to update a stipulation.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->hasPermission('update-stipulation');
    }

    /**
     * Checks to see if the user has permission to view a stipulation.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermission('view-stipulation');
    }

    /**
     * Checks to see if the user has permission to delete a stipulation.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->hasPermission('delete-stipulation');
    }
}
