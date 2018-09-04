<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Checks to see if the user has permission to view list of events.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function index(User $user)
    {
        return $user->hasPermission('view-events');
    }

    /**
     * Checks to see if the user has permission to create an event.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('create-event');
    }

    /**
     * Checks to see if the user has permission to edit an event.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function edit(User $user)
    {
        return $user->hasPermission('edit-event');
    }

    /**
     * Checks to see if the user has permission to view an event.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermission('view-event');
    }

    /**
     * Checks to see if the user has permission to delete an event.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->hasPermission('delete-event');
    }

    public function update(User $user)
    {
        return $user->hasPermission('update-event');
    }

    /**
     * Checks to see if the user has permission to archive an event.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function archive(User $user)
    {
        return $user->hasPermission('archive-event');
    }

    /**
     * Checks to see if the user has permission to edit event results.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function updateResults(User $user)
    {
        return $user->hasPermission('edit-event-results');
    }
}
