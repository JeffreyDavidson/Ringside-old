<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;

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
     * @return boolean
     */
    public function hasPermission($permissionSlug)
    {
        return $this->role->permissions->contains('slug', $permissionSlug);
    }

    /**
     * Determine if the supplied role matches the role of the user.
     *
     * @param  Role $role
     * @return boolean
     */
    public function hasRole(Role $role)
    {
        return $this->role == $role;
    }
}
