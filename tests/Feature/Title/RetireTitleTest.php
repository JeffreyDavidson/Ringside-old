<?php

namespace Tests\Feature\Title;

use App\Models\Title;
use Tests\IntegrationTestCase;

class RetireTitleTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('retire-title');
    }

    /** @test */
    public function users_who_have_permission_can_retire_a_title()
    {
        $title = factory(Title::class)->create();

        $response = $this->actingAs($this->authorizedUser)->post(route('titles.retire', $title->id));

        $response->assertStatus(302);
        tap($title->fresh(), function ($title) {
            $this->assertNotNull($title->retirements->first()->retired_at);
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_retire_a_title()
    {
        $title = factory(Title::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->post(route('titles.retire', $title->id));

        $response->assertStatus(403);
        tap($title->fresh(), function ($title) {
            $this->assertTrue($title->retirements->isEmpty());
        });
    }

    /** @test */
    public function guests_cannot_retire_a_title()
    {
        $title = factory(Title::class)->create();

        $response = $this->post(route('titles.retire', $title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_active_title_that_when_retired_will_redirect_the_user_to_the_active_titles_page()
    {
        $title = factory(Title::class)->states('active')->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('active-titles.index'))->post(route('titles.retire', $title->id));

        $response->assertRedirect(route('active-titles.index'));
    }

    /** @test */
    public function an_inactive_title_that_when_retired_will_redirect_the_user_to_the_inactive_titles_page()
    {
        $title = factory(Title::class)->states('inactive')->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('inactive-titles.index'))->post(route('titles.retire', $title->id));

        $response->assertRedirect(route('inactive-titles.index'));
    }
}
