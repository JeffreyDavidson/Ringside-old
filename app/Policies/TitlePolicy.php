<?php

namespace App\Policies;

use App\Models\User;
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
    public function show(User $user)
    {
        return $user->hasPermission('show-title');
    }

    /**
     * Checks to see if the user has permission to edit a title.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function edit(User $user)
    {
        return $user->hasPermission('edit-title');
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
     * Checks to see if the user has permission to retire a title.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function retire(User $user)
    {
        return $user->hasPermission('retire-title');
    }
}
