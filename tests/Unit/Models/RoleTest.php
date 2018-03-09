<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected $role;

    public function setUp()
    {
        parent::setUp();

        $this->role = factory(Role::class)->create();
    }

    /** @test */
    public function a_role_has_many_permissions()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->role->permissions);
    }

    /** @test */
    public function a_role_can_be_given_permissions()
    {
        $permission = factory(Permission::class)->make();

        $this->role->givePermissionTo($permission);

        $this->assertContains($permission->id, $this->role->permissions->pluck('id'));
    }
}
