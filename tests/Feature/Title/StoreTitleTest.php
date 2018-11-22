<?php

namespace Tests\Feature\Title;

use App\Models\Title;
use Carbon\Carbon;
use Tests\IntegrationTestCase;

class StoreTitleTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-title']);
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
    public function users_who_have_permission_can_store_a_title()
    {
        $response = $this->actingAs($this->authorizedUser)->post(route('titles.store'), $this->validParams());

        $response->assertStatus(302);
        tap(Title::first(), function ($title) {
            $this->assertEquals('Title Name', $title->name);
            $this->assertEquals('title-slug', $title->slug);
            $this->assertEquals('2017-08-04', $title->introduced_at->toDateString());
        });
    }

    /** @test */
    public function a_title_that_is_introduced_today_or_before_is_set_as_active()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('titles.create'))->post(route('titles.store'), $this->validParams([
            'introduced_at' => Carbon::today()->toDateString(),
        ]));

        $response->assertRedirect(route('active-titles.index'));
        tap(Title::first(), function ($title) use ($response) {
            $this->assertTrue($title->isActive());
        });
    }

    /** @test */
    public function a_title_that_is_hired_after_today_is_set_as_inactive()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('titles.create'))->post(route('titles.store'), $this->validParams([
            'introduced_at' => Carbon::tomorrow()->toDateString(),
        ]));

        $response->assertRedirect(route('inactive-titles.index'));
        tap(Title::first(), function ($title) {
            $this->assertFalse($title->isActive());
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_store_a_title()
    {
        $response = $this->actingAs($this->unauthorizedUser)->post(route('titles.store'), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_store_a_title()
    {
        $response = $this->post(route('titles.store'), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function title_name_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('titles.create'))->post(route('titles.store'), $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function title_name_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('titles.create'))->post(route('titles.store'), $this->validParams([
            'name' => [],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function title_name_must_be_unique()
    {
        factory(Title::class)->create(['name' => 'Title Name']);

        $response = $this->actingAs($this->authorizedUser)->from(route('titles.create'))->post(route('titles.store'), $this->validParams([
            'name' => 'Title Name',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Title::count());
    }

    /** @test */
    public function title_slug_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('titles.create'))->post(route('titles.store'), $this->validParams([
            'slug' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('slug');
    }

    /** @test */
    public function title_slug_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('titles.create'))->post(route('titles.store'), $this->validParams([
            'slug' => [],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('slug');
    }

    /** @test */
    public function title_slug_must_be_unique()
    {
        factory(Title::class)->create(['slug' => 'title-slug']);

        $response = $this->actingAs($this->authorizedUser)->from(route('titles.create'))->post(route('titles.store'), $this->validParams([
            'slug' => 'title-slug',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Title::count());
    }

    /** @test */
    public function title_introduced_at_date_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('titles.create'))->post(route('titles.store'), $this->validParams([
            'introduced_at' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('introduced_at');
    }

    /** @test */
    public function title_introduced_at_date_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('titles.create'))->post(route('titles.store'), $this->validParams([
            'introduced_at' => [],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('introduced_at');
    }

    /** @test */
    public function title_introduced_at_date_must_be_in_a_valid_date_format()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('titles.create'))->post(route('titles.store'), $this->validParams([
            'introduced_at' => 'not-a-valid-date',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('introduced_at');
    }
}
