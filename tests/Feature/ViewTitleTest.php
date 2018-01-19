<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Title;
use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewTitleTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    private $role;

    private $permission;

    private $title;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['slug' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'show-title']);
        $this->title = factory(Title::class)->create([
            'name' => 'Title Name',
            'slug' => 'title-slug',
            'introduced_at' => '2017-09-17'
        ]);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    public function users_who_have_permission_can_view_a_title()
    {
        $response = $this->actingAs($this->user)->get(route('titles.show', $this->title->id));

        $response->assertSuccessful();
        $response->assertViewIs('titles.show');
        $response->assertViewHas('title');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_a_title()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('titles.show', $this->title->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_a_title()
    {
        $response = $this->get(route('titles.show', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
