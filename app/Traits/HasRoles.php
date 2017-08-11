<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;

trait HasRoles {

	abstract public function role();

    public function assignRole(Role $role)
    {
        return $this->role()->associate($role);
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param  string $permissionSlug
     * @return boolean
     */
    public function hasPermission($permissionSlug)
    {
        return $this->role->permissions->contains('slug', $permissionSlug);
    }
}
