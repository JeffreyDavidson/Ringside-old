<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ViewTitleListTest extends TestCase
{
    use DatabaseMigrations;

    private $user;
    private $role;
    private $permission;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['slug' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'view-titles']);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_titles()
    {
        $titleA = factory(Title::class)->create();
        $titleB = factory(Title::class)->create();
        $titleC = factory(Title::class)->create();

        $response = $this->actingAs($this->user)
                        ->get(route('titles.index'));

        $response->assertStatus(200);
        $response->data('titles')->assertEquals([
            $titleA,
            $titleB,
            $titleC,
        ]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_titles()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)
                        ->get(route('titles.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_title_list()
    {
        $response = $this->get(route('titles.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
