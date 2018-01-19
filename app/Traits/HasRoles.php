<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;

trait HasRoles
{
    abstract public function role();

    /**
     * Assign a role to a user.
     *
     *
     * @param  Role $role
     * @return mixed
     */
    public function assignRole(Role $role)
    {
        return $this->role()->associate($role);
    }

    /**
     * Determine if the user's role has the given permission.
     *
     * @param  string $permissionSlug
     * @return bool
     */
    public function hasPermission($permissionSlug)
    {
        return $this->role->permissions->contains('slug', $permissionSlug);
    }

    /**
     * Determine if the supplied role matches the role of the user.
     *
     * @param  Role $role
     * @return bool
     */
    public function hasRole(Role $role)
    {
        return $this->role == $role;
    }
}
