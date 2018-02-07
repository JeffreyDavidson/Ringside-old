<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewTitleTest extends TestCase
{
    use DatabaseMigrations;

    private $title;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('show-title');

        $this->title = factory(Title::class)->create([
            'name' => 'Title Name',
            'slug' => 'title-slug',
            'introduced_at' => '2017-09-17'
        ]);
    }

    /** @test */
    public function users_who_have_permission_can_view_a_title()
    {
        $response = $this->actingAs($this->authorizedUser)->get(route('titles.show', $this->title->id));

        $response->assertSuccessful();
        $response->assertViewIs('titles.show');
        $response->assertViewHas('title');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_a_title()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('titles.show', $this->title->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_a_title()
    {
        $response = $this->get(route('titles.show', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
