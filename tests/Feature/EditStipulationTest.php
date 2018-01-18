<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Stipulation;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class EditStipulationTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    private $role;

    private $permission;

    private $stipulation;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['slug' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'edit-stipulation']);
        $this->stipulation = factory(Stipulation::class)->create($this->oldAttributes());

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'name' => 'Old Name',
            'slug' => 'old-slug',
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Stipulation Name',
            'slug' => 'stipulation-slug',
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_edit_stipulation_form()
    {
        $response = $this->actingAs($this->user)->get(route('stipulations.edit', $this->stipulation->id));

        $response->assertSuccessful();
        $this->assertTrue($response->data('stipulation')->is($this->stipulation));
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_stipulation_form()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('stipulations.edit', $this->stipulation->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_edit_stipulation_form()
    {
        $response = $this->get(route('stipulations.edit', $this->stipulation->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function name_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('stipulations.edit', $this->stipulation->id))->patch(route('stipulations.update', $this->stipulation->id), $this->validParams([
            'name' => '',
        ]));

        $response->assertRedirect(route('stipulations.edit', $this->stipulation->id));
        $response->assertSessionHasErrors('name');
        tap($this->stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('Old Name', $stipulation->name);
        });
    }

    /** @test */
    public function slug_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('stipulations.edit', $this->stipulation->id))->patch(route('stipulations.update', $this->stipulation->id), $this->validParams([
            'slug' => '',
        ]));

        $response->assertRedirect(route('stipulations.edit', $this->stipulation->id));
        $response->assertSessionHasErrors('slug');
        tap($this->stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('old-slug', $stipulation->slug);
        });
    }

    /** @test */
    public function name_must_be_unique()
    {
        factory(Stipulation::class)->create($this->validParams());

        $response = $this->actingAs($this->user)->from(route('stipulations.edit', $this->stipulation->id))->patch(route('stipulations.update', $this->stipulation->id), $this->validParams([
            'name' => 'Stipulation Name',
        ]));

        $response->assertRedirect(route('stipulations.edit', $this->stipulation->id));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Stipulation::where('name', 'Old Name')->count());
        tap($this->stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('Old Name', $stipulation->name);
        });
    }

    /** @test */
    public function slug_must_be_unique()
    {
        factory(Stipulation::class)->create(['slug' => 'stipulation-slug']);

        $response = $this->actingAs($this->user)->from(route('stipulations.edit', $this->stipulation->id))->patch(route('stipulations.update', $this->stipulation->id), $this->validParams([
            'slug' => 'stipulation-slug',
        ]));

        $response->assertRedirect(route('stipulations.edit', $this->stipulation->id));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Stipulation::where('slug', 'stipulation-slug')->count());
        tap($this->stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('old-slug', $stipulation->slug);
        });
    }

    /** @test */
    public function editing_a_valid_stipulation()
    {
        $response = $this->actingAs($this->user)->from(route('stipulations.edit', $this->stipulation->id))->patch(route('stipulations.update', $this->stipulation->id), [
            'name' => 'New Name',
            'slug' => 'new-slug',
        ]);

        $response->assertRedirect(route('stipulations.index'));
        tap($this->stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('New Name', $stipulation->name);
            $this->assertEquals('new-slug', $stipulation->slug);
        });
    }
}
