<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_permission_belongs_to_many_roles()
    {
        $permission = factory(Permission::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $permission->roles);
    }
}
