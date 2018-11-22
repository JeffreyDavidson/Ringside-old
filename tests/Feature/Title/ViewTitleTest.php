<?php

namespace Tests\Feature\Title;

use App\Models\Title;
use Tests\IntegrationTestCase;

class ViewTitleTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-title');
    }

    /** @test */
    public function users_who_have_permission_can_view_a_title()
    {
        $title = factory(Title::class)->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('titles.show', $title->id));

        $response->assertSuccessful();
        $response->assertViewIs('titles.show');
        $response->assertViewHas('title');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_a_title()
    {
        $title = factory(Title::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->get(route('titles.show', $title->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_a_title()
    {
        $title = factory(Title::class)->create();

        $response = $this->get(route('titles.show', $title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
