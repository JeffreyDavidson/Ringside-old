<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Match;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditTitleTest extends TestCase
{
    use RefreshDatabase;

    private $title;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['edit-title', 'update-title']);

        $this->title = factory(Title::class)->create($this->oldAttributes());
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'name' => 'Old Name',
            'slug' => 'old-slug',
            'introduced_at' => '2016-08-04'
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Title Name',
            'slug' => 'title-slug',
            'introduced_at' => '2016-08-04'
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_edit_title_form()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('titles.edit', $this->title->id));

        $response->assertStatus(200);
        $this->assertTrue($response->data('title')->is($this->title));
    }

    /** @test */
    public function users_who_have_permission_can_edit_a_title_with_matches()
    {
        $event = factory(Event::class)->create(['date' => '2016-12-19']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $match->addTitle($this->title);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('titles.edit', $this->title->id))
                        ->patch(route('titles.update', $this->title->id), [
                            'name' => 'New Name',
                            'slug' => 'new-slug',
                            'introduced_at' => '2016-12-18'
                        ]);

        $response->assertRedirect(route('titles.index'));
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('New Name', $title->name);
            $this->assertEquals('new-slug', $title->slug);
            $this->assertEquals('2016-12-18', $title->introduced_at->toDateString());
        });
    }

    /** @test */
    public function users_who_have_permission_can_edit_a_title_with_no_matches()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('titles.edit', $this->title->id))
                        ->patch(route('titles.update', $this->title->id), $this->validParams([
                            'name' => 'New Name',
                            'slug' => 'new-slug',
                            'introduced_at' => '2016-12-18'
                        ]));

        $response->assertRedirect(route('titles.index'));
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('New Name', $title->name);
            $this->assertEquals('new-slug', $title->slug);
            $this->assertEquals('2016-12-18', $title->introduced_at->toDateString());
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_title_form()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('titles.edit', $this->title->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_edit_a_title()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->patch(route('titles.update', $this->title->id), $this->validParams());

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
    public function guests_cannot_edit_a_title()
    {
        $response = $this->patch(route('titles.update', $this->title->id), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function title_name_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
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
    public function tile_name_must_be_unique()
    {
        factory(Title::class)->create(['name' => 'Title Name']);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('titles.edit', $this->title->id))
                        ->patch(route('titles.update', $this->title->id), $this->validParams([
                            'name' => 'Title Name'
                        ]));

        $response->assertRedirect(route('titles.edit', $this->title->id));
        $response->assertSessionHasErrors('name');
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('Old Name', $title->name);
        });
    }

    /** @test */
    public function title_slug_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
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
    public function title_slug_must_be_unique()
    {
        factory(Title::class)->create(['slug' => 'title-slug']);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('titles.edit', $this->title->id))
                        ->patch(route('titles.update', $this->title->id), $this->validParams([
                            'slug' => 'title-slug'
                        ]));

        $response->assertRedirect(route('titles.edit', $this->title->id));
        $response->assertSessionHasErrors('slug');
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('old-slug', $title->slug);
        });
    }

    /** @test */
    public function title_introduced_at_date_must_be_a_valid_date()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('titles.edit', $this->title->id))
                        ->patch(route('titles.update', $this->title->id), $this->validParams([
                            'introduced_at' => 'not-a-date',
                        ]));

        $response->assertRedirect(route('titles.edit', $this->title->id));
        $response->assertSessionHasErrors('introduced_at');
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('2016-08-04', $title->introduced_at->toDateString());
        });
    }

    /** @test */
    public function title_introduced_at_date_must_be_before_first_competed_for_match()
    {
        $event = factory(Event::class)->create(['date' => '2016-12-19']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $match->addTitle($this->title);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('titles.edit', $this->title->id))
                        ->patch(route('titles.update', $this->title->id), $this->validParams([
                            'introduced_at' => '2016-12-20',
                        ]));

        $response->assertRedirect(route('titles.edit', $this->title->id));
        $response->assertSessionHasErrors('introduced_at');
        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('2016-08-04', $title->introduced_at->toDateString());
        });
    }
}
