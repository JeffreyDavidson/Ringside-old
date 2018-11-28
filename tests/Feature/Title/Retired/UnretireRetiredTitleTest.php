<?php

namespace Tests\Feature\Title\Retired;

use App\Models\Title;
use Tests\IntegrationTestCase;

class UnretireRetiredTitleTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('unretire-title');
    }

    /** @test */
    public function users_who_have_permission_can_unretire_a_retired_title()
    {
        $title = factory(Title::class)->states('retired')->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('retired-titles.index'))->delete(route('retired-titles.unretire', $title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('retired-titles.index'));
        tap($title->fresh(), function ($title) {
            $this->assertNotNull($title->retirements->first()->ended_at);
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_unretire_a_retired_title()
    {
        $title = factory(Title::class)->states('retired')->create();

        $response = $this->actingAs($this->unauthorizedUser)->from(route('retired-titles.index'))->delete(route('retired-titles.unretire', $title->id));

        $response->assertStatus(403);
        tap($title->fresh(), function ($title) {
            $this->assertNull($title->retirements->first()->ended_at);
        });
    }

    /** @test */
    public function guests_cannot_unretire_a_retired_title()
    {
        $title = factory(Title::class)->states('retired')->create();

        $response = $this->from(route('retired-titles.index'))->delete(route('retired-titles.unretire', $title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
