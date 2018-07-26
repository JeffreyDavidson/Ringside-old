<?php

namespace Tests\Feature\Title;

use App\Models\Title;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddTitleTest extends TestCase
{
    use RefreshDatabase;

    private $response;

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

    private function assertFormError($field, $expectedEventCount = 0)
    {
        $this->response->assertStatus(302);
        $this->response->assertRedirect(route('titles.create'));
        $this->response->assertSessionHasErrors($field);
        $this->assertEquals($expectedEventCount, Title::count());
    }

    /** @test */
    public function users_who_have_permission_can_view_the_add_title_page()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('titles.create'));

        $response->assertSuccessful();
        $response->assertViewIs('titles.create');
    }

    /** @test */
    public function users_who_have_permission_can_create_a_title()
    {
        $this->withoutExceptionHandling();
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
    public function a_title_that_is_introduced_today_or_before_is_active()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('titles.create'))
                        ->post(route('titles.index'), $this->validParams([
                            'introduced_at' => Carbon::today()
                        ]));

        tap(Title::first(), function ($title) use ($response) {
            $this->assertTrue($title->is_active);
        });
    }

    /** @test */
    public function a_title_that_is_hired_after_today_or_before_is_inactive()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('titles.create'))
                        ->post(route('titles.index'), $this->validParams([
                            'introduced_at' => Carbon::tomorrow()
                        ]));

        tap(Title::first(), function ($title) use ($response) {
            $this->assertFalse($title->is_active);
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_title_page()
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
    public function guests_cannot_view_the_add_title_page()
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
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('titles.create'))
                            ->post(route('titles.index'), $this->validParams([
                                'name' => '',
                            ]));

        $this->assertFormError('name');
    }

    /** @test */
    public function title_name_must_be_unique()
    {
        factory(Title::class)->create(['name' => 'Title Name']);

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('titles.create'))
                            ->post(route('titles.index'), $this->validParams([
                                'name' => 'Title Name',
                            ]));

        $this->assertFormError('name', 1);
    }

    /** @test */
    public function title_slug_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('titles.create'))
                            ->post(route('titles.index'), $this->validParams([
                                'slug' => '',
                            ]));

        $this->assertFormError('slug');
    }

    /** @test */
    public function title_slug_must_be_unique()
    {
        factory(Title::class)->create(['slug' => 'title-slug']);

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('titles.create'))
                            ->post(route('titles.index'), $this->validParams([
                                'slug' => 'title-slug',
                            ]));

        $this->assertFormError('slug', 1);
    }

    /** @test */
    public function title_introduced_at_date_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('titles.create'))
                            ->post(route('titles.index'), $this->validParams([
                                'introduced_at' => '',
                            ]));

        $this->assertFormError('introduced_at');
    }

    /** @test */
    public function title_introduced_at_date_must_be_a_valid_date()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('titles.create'))
                            ->post(route('titles.index'), $this->validParams([
                                'introduced_at' => 'not-a-date',
                            ]));

        $this->assertFormError('introduced_at');
    }
}
