<?php

namespace Tests\Feature\Title\Inactive;

use App\Models\Title;
use Tests\IntegrationTestCase;

class ActivateInactiveTitleTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('activate-title');
    }

    /** @test */
    public function users_who_have_permission_can_activate_an_inactive_title()
    {
        $title = factory(Title::class)->states('inactive')->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('inactive-titles.index'))->post(route('inactive-titles.activate', $title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('inactive-titles.index'));
        tap($title->fresh(), function ($title) {
            $this->assertTrue($title->isActive());
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_activate_an_inactive_title()
    {
        $title = factory(Title::class)->states('inactive')->create();

        $response = $this->actingAs($this->unauthorizedUser)->from(route('inactive-titles.index'))->post(route('inactive-titles.activate', $title->id));

        $response->assertStatus(403);
        tap($title->fresh(), function ($title) {
            $this->assertFalse($title->isActive());
        });
    }

    /** @test */
    public function guests_cannot_activate_an_inactive_title()
    {
        $title = factory(Title::class)->states('inactive')->create();

        $response = $this->from(route('inactive-titles.index'))->post(route('inactive-titles.activate', $title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
