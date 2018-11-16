<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use App\Models\Permission;
use Tests\IntegrationTestCase;

class RoleTest extends IntegrationTestCase
{
    /** @test */
    public function a_role_has_many_permissions()
    {
        $role = factory(Role::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $role->permissions);
    }

    /** @test */
    public function a_role_can_be_given_permissions()
    {
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->make();

        $role->givePermissionTo($permission);

        $this->assertContains($permission->id, $role->permissions->pluck('id'));
    }
}
