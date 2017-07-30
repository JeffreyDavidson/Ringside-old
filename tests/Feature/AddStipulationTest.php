<?php

namespace Tests\Feature;

use App\Models\Stipulation;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddStipulationTest extends TestCase
{
    use DatabaseMigrations;

    private $user;
    private $role;
    private $permission;

    public function setUp(){
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['name' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'create-stipulation']);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole('admin');
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'My Stipulation',
            'slug' => 'mystip',
        ], $overrides);
    }

    private function from($url)
    {
        session()->setPreviousUrl(url($url));
        return $this;
    }

    /** @test */
    function users_who_have_permission_can_view_the_add_stipulation_form()
    {
        $response = $this->actingAs($this->user)->get(route('stipulations.create'));

        $response->assertStatus(200);
    }

    /** @test */
    function users_who_dont_have_permission_cannot_view_the_add_stipulation_form()
    {
        $user = factory(User::class)->create();
        factory(Role::class)->create(['name' => 'editor']);
        $user->assignRole('editor');

        $response = $this->actingAs($user)->get(route('stipulations.create'));

        $response->assertStatus(403);
    }

    /** @test */
    function guests_cannot_view_the_add_stipulation_form()
    {
        $response = $this->get(route('stipulations.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    function name_is_required()
    {
        $response = $this->actingAs($this->user)
                        ->from(route('stipulations.create'))
                        ->post(route('stipulations.index'), $this->validParams([
                            'name' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Stipulation::count());
    }

    /** @test */
    function name_must_be_unique()
    {
        $response = $this->actingAs($this->user)->post(route('stipulations.index'), $this->validParams([
            'name' => 'My Stipulation',
        ]));

        tap(Stipulation::first(), function ($stipulation) use ($response) {
            $response->assertStatus(302);
            $this->assertEquals(1, Stipulation::count());
            $response->assertRedirect(route('stipulations.index'));

            $this->assertEquals('My Stipulation', $stipulation->name);
        });

        $response = $this->actingAs($this->user)
            ->from(route('stipulations.create'))
            ->post(route('stipulations.index'), $this->validParams([
                'name' => 'My Stipulation',
            ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Stipulation::count());
    }

    /** @test */
    function slug_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('stipulations.create'))->post(route('stipulations.index'), $this->validParams([
            'slug' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(0, Stipulation::count());
    }

    /** @test */
    function slug_must_be_unique()
    {
        $response = $this->actingAs($this->user)->post(route('stipulations.index'), $this->validParams([
            'slug' => 'mystip',
        ]));

        tap(Stipulation::first(), function ($stipulation) use ($response) {
            $response->assertStatus(302);
            $this->assertEquals(1, Stipulation::count());
            $response->assertRedirect(route('stipulations.index'));

            $this->assertEquals('mystip', $stipulation->slug);
        });

        $response = $this->actingAs($this->user)->from(route('stipulations.create'))->post(route('stipulations.index'), $this->validParams([
            'slug' => 'mystip',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Stipulation::count());
    }

    /** @test */
    function adding_a_valid_stipulation()
    {
        $this->disableExceptionHandling();

        $response = $this->actingAs($this->user)->post(route('stipulations.index'), [
            'name' => 'My Stipulation',
            'slug' => 'mystip',
        ]);

        tap(Stipulation::first(), function ($stipulation) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('stipulations.index'));

            $this->assertEquals('My Stipulation', $stipulation->name);
            $this->assertEquals('mystip', $stipulation->slug);
        });
    }
}