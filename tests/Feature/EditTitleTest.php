<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Match;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Title;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

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
            'name'          => 'Old Name',
            'slug'          => 'old-slug',
            'introduced_at' => Carbon::parse('December 18, 2016'),
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name'          => 'Title Name',
            'slug'          => 'title-slug',
            'introduced_at' => '2017-08-04',
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_edit_title_form()
    {
        $response = $this->actingAs($this->user)
                        ->get(route('titles.edit', $this->title->id));

        $response->assertStatus(200);
        $this->assertTrue($response->data('title')->is($this->title));
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_title_form()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)
                        ->get(route('titles.edit', $this->title->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_edit_title_form()
    {
        $response = $this->get(route('titles.edit', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function name_is_required()
    {
        $response = $this->actingAs($this->user)
                        ->from(route('titles.edit', $this->title->id))
                        ->patch(route('titles.update', $this->title->id), $this->validParams([
                            'name' => '',
                        ]));

        $response->assertRedirect(route('titles.edit', $this->title->id));
        $response->assertSessionHasErrors('name');
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('Old Name', $title->name);
        });
    }

    /** @test */
    public function slug_is_required()
    {
        $response = $this->actingAs($this->user)
                    ->from(route('titles.edit', $this->title->id))
                    ->patch(route('titles.update', $this->title->id), $this->validParams([
                        'slug' => '',
                    ]));

        $response->assertRedirect(route('titles.edit', $this->title->id));
        $response->assertSessionHasErrors('slug');
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('old-slug', $title->slug);
        });
    }

    /** @test */
    public function name_must_be_unique()
    {
        factory(Title::class)->create($this->validParams());

        $response = $this->actingAs($this->user)
                        ->from(route('titles.edit', $this->title->id))
                        ->patch(route('titles.update', $this->title->id), $this->validParams([
                            'name' => 'Title Name',
                        ]));

        $response->assertRedirect(route('titles.edit', $this->title->id));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Title::where('name', 'Old Name')->count());
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('Old Name', $title->name);
        });
    }

    /** @test */
    public function slug_must_be_unique()
    {
        factory(Title::class)->create($this->validParams());

        $response = $this->actingAs($this->user)
                        ->from(route('titles.edit', $this->title->id))
                        ->patch(route('titles.update', $this->title->id), $this->validParams([
                            'slug' => 'title-slug',
                        ]));

        $response->assertRedirect(route('titles.edit', $this->title->id));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Title::where('slug', 'title-slug')->count());
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('old-slug', $title->slug);
        });
    }

    /** @test */
    public function introduced_at_date_must_be_a_valid_date()
    {
        $response = $this->actingAs($this->user)
                ->from(route('titles.edit', $this->title->id))
                ->patch(route('titles.update', $this->title->id), $this->validParams([
                    'introduced_at' => 'not-a-date',
                ]));

        $response->assertRedirect(route('titles.edit', $this->title->id));
        $response->assertSessionHasErrors('introduced_at');
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('old-slug', $title->slug);
        });
    }

    /** @test */
    public function introduced_at_date_must_be_before_first_competed_for_match()
    {
        $event = factory(Event::class)->create(['date' => '2016-12-19']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $match->addTitle($this->title);

        $response = $this->actingAs($this->user)
            ->from(route('titles.edit', $this->title->id))
            ->patch(route('titles.update', $this->title->id), $this->validParams([
                'introduced_at' => '2016-12-20',
            ]));
        $response->assertRedirect(route('titles.edit', $this->title->id));
        $response->assertSessionHasErrors('introduced_at');
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals(Carbon::parse('2016-12-18'), $title->introduced_at);
        });
    }

    /** @test */
    public function editing_a_valid_title_with_no_matches()
    {
        $response = $this->actingAs($this->user)
                        ->from(route('titles.edit', $this->title->id))
                        ->patch(route('titles.update', $this->title->id), $this->validParams([
                            'name'          => 'New Name',
                            'slug'          => 'new-slug',
                            'introduced_at' => '2016-12-18',
                        ]));

        $response->assertRedirect(route('titles.index'));
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('New Name', $title->name);
            $this->assertEquals('new-slug', $title->slug);
            $this->assertEquals(Carbon::parse('2016-12-18'), $title->introduced_at);
        });
    }

    /** @test */
    public function editing_a_valid_title_with_matches()
    {
        $event = factory(Event::class)->create(['date' => '2016-12-19']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $match->addTitle($this->title);
        $response = $this->actingAs($this->user)
            ->from(route('titles.edit', $this->title->id))
            ->patch(route('titles.update', $this->title->id), [
                'name'          => 'New Name',
                'slug'          => 'new-slug',
                'introduced_at' => '2016-12-18',
            ]);

        $response->assertRedirect(route('titles.index'));
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('New Name', $title->name);
            $this->assertEquals('new-slug', $title->slug);
            $this->assertEquals(Carbon::parse('2016-12-18'), $title->introduced_at);
        });
    }
}
