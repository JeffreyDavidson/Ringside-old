<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Roster\RosterMember;
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
     * Checks to see if the user has permission to view a roster member.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermission('view-roster-member');
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
     * Checks to see if the user has permission to activate a roster member.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Roster\Wrestler  $wrestler
     * @return bool
     */
    public function activate(User $user, RosterMember $rosterMember)
    {
        return $user->hasPermission('activate-roster-member') && $rosterMember->hired_at->lte(today());
    }

    /**
     * Checks to see if the user has permission to deactivate a roster member.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function deactivate(User $user)
    {
        return $user->hasPermission('deactivate-roster-member');
    }

    /**
     * Checks to see if the user has permission to retire a roster member.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Roster\Wrestler  $wrestler
     * @return bool
     */
    public function retire(User $user, RosterMember $rosterMember)
    {
        return $user->hasPermission('retire-roster-member') && $rosterMember->hired_at->lte(today());
    }

    /**
     * Checks to see if the user has permission to retire a roster member.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function unretire(User $user)
    {
        return $user->hasPermission('unretire-roster-member');
    }
}
