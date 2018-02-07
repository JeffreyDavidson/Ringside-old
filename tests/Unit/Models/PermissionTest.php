<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PermissionTest extends TestCase
{
    use DatabaseMigrations;

    protected $permission;

    public function setUp()
    {
        parent::setUp();

        $this->permission = factory(Permission::class)->create();
    }

    /** @test */
    public function a_permission_belongs_to_many_roles()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->permission->roles);
    }
}
