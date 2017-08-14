<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Title;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditTitleTest extends TestCase
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
        $this->permission = factory(Permission::class)->create(['slug' => 'edit-title']);
        $this->title = factory(Title::class)->create($this->oldAttributes());

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
            'name' => 'Title Name',
            'slug' => 'title-slug',
        ], $overrides);
    }

    /** @test */
    function users_who_have_permission_can_view_the_edit_title_form()
    {
        $response = $this->actingAs($this->user)
                        ->get(route('titles.edit', $this->title->id));

        $response->assertStatus(200);
        $this->assertTrue($response->data('title')->is($this->title));
    }

    /** @test */
    function users_who_dont_have_permission_cannot_view_the_edit_title_form()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)
                        ->get(route('titles.edit', $this->title->id));

        $response->assertStatus(403);
    }

    /** @test */
    function guests_cannot_view_the_edit_title_form()
    {
        $response = $this->get(route('titles.edit', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    function name_is_required()
    {
        $response = $this->actingAs($this->user)
                        ->from(route('titles.edit', $this->title->id))
                        ->patch(route('titles.update', $this->title->id), $this->validParams([
                            'name' => ''
                        ]));

        $response->assertRedirect(route('titles.edit', $this->title->id));
        $response->assertSessionHasErrors('name');
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('Old Name', $title->name);
        });
    }

    /** @test */
    function slug_is_required()
    {
        $response = $this->actingAs($this->user)
                    ->from(route('titles.edit', $this->title->id))
                    ->patch(route('titles.update', $this->title->id), $this->validParams([
                        'slug' => ''
                    ]));

        $response->assertRedirect(route('titles.edit', $this->title->id));
        $response->assertSessionHasErrors('slug');
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('old-slug', $title->slug);
        });
    }

    /** @test */
    function name_must_be_unique()
    {
        factory(Title::class)->create($this->validParams());

        $response = $this->actingAs($this->user)
                        ->from(route('titles.edit', $this->title->id))
                        ->patch(route('titles.update', $this->title->id), $this->validParams([
                            'name' => 'Title Name'
                        ]));

        $response->assertRedirect(route('titles.edit', $this->title->id));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Title::where('name', 'Old Name')->count());
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('Old Name', $title->name);
        });
    }

    /** @test */
    function slug_must_be_unique()
    {
        factory(Title::class)->create($this->validParams());

        $response = $this->actingAs($this->user)
                        ->from(route('titles.edit', $this->title->id))
                        ->patch(route('titles.update', $this->title->id), $this->validParams([
                            'slug' => 'title-slug'
                        ]));

        $response->assertRedirect(route('titles.edit', $this->title->id));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Title::where('slug', 'title-slug')->count());
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('old-slug', $title->slug);
        });
    }

    /** @test */
    function editing_a_valid_title()
    {
        $response = $this->actingAs($this->user)
                        ->from(route('titles.edit', $this->title->id))
                        ->patch(route('titles.update', $this->title->id), [
                            'name' => 'New Name',
                            'slug' => 'new-slug',
                            'introduced_at' => '2017-12-18'
                        ]);

        $response->assertRedirect(route('titles.index'));
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('New Name', $title->name);
            $this->assertEquals('new-slug', $title->slug);
            $this->assertEquals(Carbon::parse('2017-12-18'), $title->introduced_at);
        });
    }
}
