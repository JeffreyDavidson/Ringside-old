<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;

trait HasRoles
{
    /**
     * A user can have many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Assigns a role to a user.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function assignRole(Role $role)
    {
        return $this->role()->associate($role);
    }

    /**
     * Checks to see if the user's role has the given permission.
     *
     * @param  string  $permissionSlug
     * @return bool
     */
    public function hasPermission($permissionSlug)
    {
        return $this->role->permissions->contains('slug', $permissionSlug);
    }

    /**
     * Check to see if the supplied role matches the user's role.
     *
     * @param  \App\Models\Role  $role
     * @return bool
     */
    public function hasRole(Role $role)
    {
        return $this->role == $role;
    }
}
