<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Title;
use Illuminate\Auth\Access\HandlesAuthorization;

class TitlePolicy
{
    use HandlesAuthorization;

    /**
     * Checks to see if the user has permission to view list of titles.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function index(User $user)
    {
        return $user->hasPermission('view-titles');
    }

    /**
     * Checks to see if the user has permission to create a title.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('create-title');
    }

    /**
     * Checks to see if the user has permission to view a title.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermission('view-title');
    }

    /**
     * Checks to see if the user has permission to update a title.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->hasPermission('update-title');
    }

    /**
     * Checks to see if the user has permission to delete a title.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->hasPermission('delete-title');
    }

    /**
     * Checks to see if the user has permission to activate a title.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function activate(User $user, Title $title)
    {
        return $user->hasPermission('activate-title') && $title->introduced_at->lte(today());
    }

    /**
     * Checks to see if the user has permission to deactivate a title.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function deactivate(User $user)
    {
        return $user->hasPermission('deactivate-title');
    }

    /**
     * Checks to see if the user has permission to retire a title.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function retire(User $user, Title $title)
    {
        return $user->hasPermission('retire-title') && $title->introduced_at->lte(today());
    }

    /**
     * Checks to see if the user has permission to unretire a title.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function unretire(User $user)
    {
        return $user->hasPermission('unretire-title');
    }
}
