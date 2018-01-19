<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Title;
use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddTitleTest extends TestCase
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
        $this->permission = factory(Permission::class)->create(['slug' => 'create-title']);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Title Name',
            'slug' => 'title-slug',
            'introduced_at' => '2017-08-04',
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_add_title_form()
    {
        $response = $this->actingAs($this->user)->get(route('titles.create'));

        $response->assertStatus(200);
        $response->assertViewIs('titles.create');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_title_form()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('titles.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_add_title_form()
    {
        $response = $this->get(route('titles.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function name_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('titles.create'))->post(route('titles.index'), $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Title::count());
    }

    /** @test */
    public function name_must_be_unique()
    {
        factory(Title::class)->create(['name' => 'Title Name']);

        $response = $this->actingAs($this->user)->from(route('titles.create'))->post(route('titles.index'), $this->validParams([
            'name' => 'Title Name',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Title::where('name', 'Title Name')->count());
    }

    /** @test */
    public function slug_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('titles.create'))->post(route('titles.index'), $this->validParams([
            'slug' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(0, Title::count());
    }

    /** @test */
    public function slug_must_be_unique()
    {
        factory(Title::class)->create(['slug' => 'title-slug']);

        $response = $this->actingAs($this->user)->from(route('titles.create'))->post(route('titles.index'), $this->validParams([
            'slug' => 'title-slug',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Title::where('slug', 'title-slug')->count());
    }

    /** @test */
    public function introduced_at_date_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('titles.create'))->post(route('titles.index'), $this->validParams([
            'introduced_at' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('introduced_at');
        $this->assertEquals(0, Title::count());
    }

    /** @test */
    public function introduced_at_date_must_be_a_valid_date()
    {
        $response = $this->actingAs($this->user)->from(route('titles.create'))->post(route('titles.index'), $this->validParams([
            'introduced_at' => 'not-a-date',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('introduced_at');
        $this->assertEquals(0, Title::count());
    }

    /** @test */
    public function adding_a_valid_title()
    {
        $response = $this->actingAs($this->user)->post(route('titles.index'), $this->validParams());

        tap(Title::first(), function ($title) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('titles.index'));

            $this->assertEquals('Title Name', $title->name);
            $this->assertEquals('title-slug', $title->slug);
            $this->assertEquals(Carbon::parse('2017-08-04'), $title->introduced_at);
        });
    }
}
