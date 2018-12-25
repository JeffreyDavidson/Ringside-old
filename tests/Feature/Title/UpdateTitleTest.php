<?php

namespace Tests\Feature\Title;

use App\Models\Event;
use App\Models\Title;
use Facades\MatchFactory;
use Tests\IntegrationTestCase;

class UpdateTitleTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['update-title']);
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'name' => 'Old Name',
            'slug' => 'old-slug',
            'introduced_at' => '2016-08-04',
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Title Name',
            'slug' => 'title-slug',
            'introduced_at' => '2016-08-04',
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_edit_a_title_with_a_match_as_long_as_introduced_at_date_is_before_first_match()
    {
        $title = factory(Title::class)->create($this->oldAttributes());
        $event = factory(Event::class)->create(['date' => '2016-12-19']);
        $match = MatchFactory::forEvent($event)->withTitle($title)->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('titles.edit', $title->id))->patch(route('titles.update', $title->id), [
            'name' => 'New Name',
            'slug' => 'new-slug',
            'introduced_at' => '2016-12-18',
        ]);

        tap($title->fresh(), function ($title) {
            $this->assertEquals('New Name', $title->name);
            $this->assertEquals('new-slug', $title->slug);
            $this->assertEquals('2016-12-18', $title->introduced_at->toDateString());
        });
    }

    /** @test */
    public function users_who_have_permission_can_edit_a_title_without_a_match()
    {
        $title = factory(Title::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('titles.edit', $title->id))->patch(route('titles.update', $title->id), $this->validParams([
            'name' => 'New Name',
            'slug' => 'new-slug',
            'introduced_at' => '2016-12-18',
        ]));

        tap($title->fresh(), function ($title) {
            $this->assertEquals('New Name', $title->name);
            $this->assertEquals('new-slug', $title->slug);
            $this->assertEquals('2016-12-18', $title->introduced_at->toDateString());
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_edit_a_title()
    {
        $title = factory(Title::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->patch(route('titles.update', $title->id), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_update_a_title()
    {
        $title = factory(Title::class)->create();

        $response = $this->patch(route('titles.update', $title->id), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function title_name_is_required()
    {
        $this->withoutExceptionHandling();
        $title = factory(Title::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('titles.edit', $title->id))->patch(route('titles.update', $title->id), $this->validParams([
            'name' => '',
        ]));

        $response->assertRedirect(route('titles.edit', $title->id));
        $response->assertSessionHasErrors('name');
        tap($title->fresh(), function ($title) {
            $this->assertEquals('Old Name', $title->name);
        });
    }

    /** @test */
    public function title_name_must_be_a_string()
    {
        $title = factory(Title::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('titles.edit', $title->id))->patch(route('titles.update', $title->id), $this->validParams([
            'name' => [],
        ]));

        $response->assertRedirect(route('titles.edit', $title->id));
        $response->assertSessionHasErrors('name');
        tap($title->fresh(), function ($title) {
            $this->assertEquals('Old Name', $title->name);
        });
    }

    /** @test */
    public function tile_name_must_be_unique()
    {
        $title = factory(Title::class)->create($this->oldAttributes());
        factory(Title::class)->create(['name' => 'Title Name']);

        $response = $this->actingAs($this->authorizedUser)->from(route('titles.edit', $title->id))->patch(route('titles.update', $title->id), $this->validParams([
            'name' => 'Title Name',
        ]));

        $response->assertRedirect(route('titles.edit', $title->id));
        $response->assertSessionHasErrors('name');
        tap($title->fresh(), function ($title) {
            $this->assertEquals('Old Name', $title->name);
        });
    }

    /** @test */
    public function title_slug_is_required()
    {
        $title = factory(Title::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('titles.edit', $title->id))->patch(route('titles.update', $title->id), $this->validParams([
            'slug' => '',
        ]));

        $response->assertRedirect(route('titles.edit', $title->id));
        $response->assertSessionHasErrors('slug');
        tap($title->fresh(), function ($title) {
            $this->assertEquals('old-slug', $title->slug);
        });
    }

    /** @test */
    public function title_slug_must_be_a_string()
    {
        $title = factory(Title::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('titles.edit', $title->id))->patch(route('titles.update', $title->id), $this->validParams([
            'slug' => [],
        ]));

        $response->assertRedirect(route('titles.edit', $title->id));
        $response->assertSessionHasErrors('slug');
        tap($title->fresh(), function ($title) {
            $this->assertEquals('old-slug', $title->slug);
        });
    }

    /** @test */
    public function title_slug_must_be_unique()
    {
        $title = factory(Title::class)->create($this->oldAttributes());
        factory(Title::class)->create(['slug' => 'title-slug']);

        $response = $this->actingAs($this->authorizedUser)->from(route('titles.edit', $title->id))->patch(route('titles.update', $title->id), $this->validParams([
            'slug' => 'title-slug',
        ]));

        $response->assertRedirect(route('titles.edit', $title->id));
        $response->assertSessionHasErrors('slug');
        tap($title->fresh(), function ($title) {
            $this->assertEquals('old-slug', $title->slug);
        });
    }

    /** @test */
    public function title_introduced_is_required()
    {
        $title = factory(Title::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('titles.edit', $title->id))->patch(route('titles.update', $title->id), $this->validParams([
            'introduced_at' => '',
        ]));

        $response->assertRedirect(route('titles.edit', $title->id));
        $response->assertSessionHasErrors('introduced_at');
        tap($title->fresh(), function ($title) {
            $this->assertEquals('2016-08-04', $title->introduced_at->toDateString());
        });
    }

    /** @test */
    public function title_introduced_must_be_a_string()
    {
        $title = factory(Title::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('titles.edit', $title->id))->patch(route('titles.update', $title->id), $this->validParams([
            'introduced_at' => [],
        ]));

        $response->assertRedirect(route('titles.edit', $title->id));
        $response->assertSessionHasErrors('introduced_at');
        tap($title->fresh(), function ($title) {
            $this->assertEquals('2016-08-04', $title->introduced_at->toDateString());
        });
    }

    /** @test */
    public function title_introduced_at_date_must_be_a_valid_date_format()
    {
        $title = factory(Title::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('titles.edit', $title->id))->patch(route('titles.update', $title->id), $this->validParams([
            'introduced_at' => 'not-a-valid-date',
        ]));

        $response->assertRedirect(route('titles.edit', $title->id));
        $response->assertSessionHasErrors('introduced_at');
        tap($title->fresh(), function ($title) {
            $this->assertEquals('2016-08-04', $title->introduced_at->toDateString());
        });
    }

    /** @test */
    public function title_introduced_at_date_must_be_before_first_competed_for_match()
    {
        $title = factory(Title::class)->create($this->oldAttributes());
        $event = factory(Event::class)->create(['date' => '2016-12-19']);
        MatchFactory::forEvent($event)->withTitle($title)->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('titles.edit', $title->id))->patch(route('titles.update', $title->id), $this->validParams([
            'introduced_at' => '2016-12-20',
        ]));

        $response->assertRedirect(route('titles.edit', $title->id));
        $response->assertSessionHasErrors('introduced_at');
        tap($title->fresh(), function ($title) {
            $this->assertEquals('2016-08-04', $title->introduced_at->toDateString());
        });
    }
}
