<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;

trait HasRoles {

	abstract public function role();

    public function assignRole($role)
    {
        return $this->update(['role_id' => Role::whereName($role)->firstOrFail()]);
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('slug', $role);
        }

        return !! $role->intersect($this->roles)->count();
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param  Permission $permission
     * @return boolean
     */
    public function hasPermission(Permission $permission)
    {
        return $this->hasRole($permission->roles);
    }
}