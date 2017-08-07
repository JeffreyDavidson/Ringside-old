<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TitlePolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->can('view-titles');
    }

    public function create(User $user)
    {
        return $user->can('create-title');
    }

    public function show(User $user)
    {
        return $user->can('show-title');
    }

    public function edit(User $user)
    {
        return $user->can('edit-title');
    }

    public function delete(User $user)
    {
        return $user->can('delete-title');
    }
}
