<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddTitleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-title', 'store-title']);
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
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('titles.create'));

        $response->assertStatus(200);
    }

    /** @test */
    public function users_who_have_permission_can_create_a_title()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->post(route('titles.index'), $this->validParams());

        tap(Title::first(), function ($title) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('titles.index'));

            $this->assertEquals('Title Name', $title->name);
            $this->assertEquals('title-slug', $title->slug);
            $this->assertEquals('2017-08-04', $title->introduced_at->toDateString());
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_title_form()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('titles.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_create_a_title()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->post(route('titles.index'), $this->validParams());

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
     public function guests_cannot_create_a_title()
     {
        $response = $this->post(route('titles.index'), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
     }

    /** @test */
    public function title_name_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('titles.create'))
                        ->post(route('titles.index'), $this->validParams([
                            'name' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Title::count());
    }

    /** @test */
    public function title_name_must_be_unique()
    {
        factory(Title::class)->create(['name' => 'Title Name']);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('titles.create'))
                        ->post(route('titles.index'), $this->validParams([
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
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('titles.create'))
                        ->post(route('titles.index'), $this->validParams([
                            'slug' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(0, Title::count());
    }

    /** @test */
    public function title_slug_must_be_unique()
    {
        factory(Title::class)->create(['slug' => 'title-slug']);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('titles.create'))
                        ->post(route('titles.index'), $this->validParams([
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
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('titles.create'))
                        ->post(route('titles.index'), $this->validParams([
                            'introduced_at' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('introduced_at');
        $this->assertEquals(0, Title::count());
    }

    /** @test */
    public function title_introduced_at_date_must_be_a_valid_date()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('titles.create'))
                        ->post(route('titles.index'), $this->validParams([
                            'introduced_at' => 'not-a-date',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('introduced_at');
        $this->assertEquals(0, Title::count());
    }
}
