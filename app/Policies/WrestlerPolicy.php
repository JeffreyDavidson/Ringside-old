<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wrestler;
use Illuminate\Auth\Access\HandlesAuthorization;

class WrestlerPolicy
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
        return $user->hasPermission('view-wrestlers');
    }

    /**
     * Checks to see if the user has permission to create a roster member.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('create-wrestler');
    }

    /**
     * Checks to see if the user has permission to view a roster member.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermission('view-wrestler');
    }

    /**
     * Checks to see if the user has permission to update a roster member.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->hasPermission('update-wrestler');
    }

    /**
     * Checks to see if the user has permission to delete a roster member.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->hasPermission('delete-wrestler');
    }

    /**
     * Checks to see if the user has permission to activate a roster member.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Wrestler  $wrestler
     * @return bool
     */
    public function activate(User $user, Wrestler $wrestler)
    {
        return $user->hasPermission('activate-wrestler') && $wrestler->hired_at->lte(today());
    }

    /**
     * Checks to see if the user has permission to deactivate a roster member.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function deactivate(User $user)
    {
        return $user->hasPermission('deactivate-wrestler');
    }

    /**
     * Checks to see if the user has permission to retire a roster member.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Wrestler  $wrestler
     * @return bool
     */
    public function retire(User $user, Wrestler $wrestler)
    {
        return $user->hasPermission('retire-wrestler') && $wrestler->hired_at->lte(today());
    }

    /**
     * Checks to see if the user has permission to retire a roster member.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function unretire(User $user)
    {
        return $user->hasPermission('unretire-wrestler');
    }
}
