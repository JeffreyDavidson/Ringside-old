<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RosterMemberPolicy
{
    use HandlesAuthorization;

    /**
     * Checks to see if the user has permission to view list of roster members.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function index(User $user)
    {
        return $user->hasPermission('view-roster-members');
    }

    /**
     * Checks to see if the user has permission to create a roster member.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('create-roster-member');
    }

    /**
     * Checks to see if the user has permission to view a roster-= member.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermission('view-roster-member');
    }

    /**
     * Checks to see if the user has permission to edit a roster member.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function edit(User $user)
    {
        return $user->hasPermission('edit-roster-member');
    }

    /**
     * Checks to see if the user has permission to update a roster member.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->hasPermission('update-roster-member');
    }

    /**
     * Checks to see if the user has permission to delete a roster member.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->hasPermission('delete-roster-member');
    }

    /**
     * Checks to see if the user has permission to retire a roster member.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function retire(User $user)
    {
        return $user->hasPermission('retire-roster-member');
    }
}
