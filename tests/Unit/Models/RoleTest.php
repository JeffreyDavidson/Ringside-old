<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

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
