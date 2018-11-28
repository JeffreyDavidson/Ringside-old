<?php

namespace Tests\Unit\Models;

use App\Models\Permission;
use Tests\IntegrationTestCase;

class PermissionTest extends IntegrationTestCase
{
    /** @test */
    public function a_permission_belongs_to_many_roles()
    {
        $permission = factory(Permission::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $permission->roles);
    }
}
