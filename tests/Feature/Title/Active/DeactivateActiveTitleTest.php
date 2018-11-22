<?php

namespace Tests\Feature\Title\Active;

use App\Models\Title;
use Tests\IntegrationTestCase;

class DeactivateActiveTitleTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('deactivate-title');
    }

    /** @test */
    public function users_who_have_permission_can_deactivate_an_active_title()
    {
        $title = factory(Title::class)->states('active')->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('active-titles.index'))->delete(route('active-titles.deactivate', $title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('active-titles.index'));
        tap($title->fresh(), function ($title) {
            $this->assertFalse($title->isActive());
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_deactivate_an_active_title()
    {
        $title = factory(Title::class)->states('active')->create();

        $response = $this->actingAs($this->unauthorizedUser)->from(route('active-titles.index'))->delete(route('active-titles.deactivate', $title->id));

        $response->assertStatus(403);
        tap($title->fresh(), function ($title) {
            $this->assertTrue($title->isActive());
        });
    }

    /** @test */
    public function guests_cannot_deactivate_an_active_title()
    {
        $title = factory(Title::class)->states('active')->create();

        $response = $this->from(route('active-titles.index'))->delete(route('active-titles.deactivate', $title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
