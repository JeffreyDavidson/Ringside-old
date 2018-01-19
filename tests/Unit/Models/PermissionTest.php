<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PermissionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_permission_belongs_to_a_role()
    {
        $permission = factory(Permission::class)->create();
        $role = factory(Role::class)->create();

        $role->givePermissionTo($permission);

        $this->assertEquals(1, $permission->roles->count());
    }
}
