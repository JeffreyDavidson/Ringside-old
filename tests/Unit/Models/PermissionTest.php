<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use App\Models\Permission;
use Tests\IntegrationTestCase;

class PermissionTest extends IntegrationTestCase
{
    /** @test */
    public function a_permission_belongs_to_many_roles()
    {
        $permission = factory(Permission::class)->create();
        $roleA = factory(Role::class)->create();
        $roleB = factory(Role::class)->create();
        $roleC = factory(Role::class)->create();

        $permission->roles()->attach($roleA->id);
        $permission->roles()->attach($roleB->id);
        $permission->roles()->attach($roleC->id);

        $this->assertCount(3, $permission->roles);
    }
}
