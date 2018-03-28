<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Policies\ApplicationPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApplicationPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_get_permissions_for_gate()
    {

        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $permission1 = factory(Permission::class)->create(['slug' => 'perm1']);
        $permission2 = factory(Permission::class)->create(['slug' => 'perm2']);
        $permission3 = factory(Permission::class)->create(['slug' => 'perm3']);

        $role->givePermissionTo($permission1);
        $role->givePermissionTo($permission2);
        $user->assignRole($role);

        (new ApplicationPolicy())->init();

        // $this->assertCount(3, (new ApplicationPolicy())->getPermissions());
        $this->assertTrue(Gate::allows('perm1'));
        $this->assertTrue(Gate::allows('perm2'));
        $this->assertFalse(Gate::allows('perm3'));

    }
}
