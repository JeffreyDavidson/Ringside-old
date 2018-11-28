<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Tests\IntegrationTestCase;

class UserTest extends IntegrationTestCase
{
    /** @test */
    public function a_user_has_a_role()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(Role::class, $user->role);
    }

    /** @test */
    public function a_user_can_be_assigned_a_role()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $user->assignRole($role);

        $this->assertTrue($user->hasRole($role));
    }

    /** @test */
    public function a_user_with_a_role_has_a_given_permission()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();

        $user->assignRole($role);
        $role->givePermissionTo($permission);

        $this->assertTrue($user->hasPermission($permission->slug));
    }
}
