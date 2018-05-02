<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TitlePolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->hasPermission('view-titles');
    }

    public function create(User $user)
    {
        return $user->hasPermission('create-title');
    }

    public function show(User $user)
    {
        return $user->hasPermission('show-title');
    }

    public function edit(User $user)
    {
        return $user->hasPermission('edit-title');
    }

    public function delete(User $user)
    {
        return $user->hasPermission('delete-title');
    }

    public function retire(User $user)
    {
        return $user->hasPermission('retire-title');
    }
}
