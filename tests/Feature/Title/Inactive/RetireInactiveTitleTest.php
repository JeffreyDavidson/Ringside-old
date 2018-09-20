<?php

namespace Tests\Feature\Title;

use App\Models\Title;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RetireInactiveTitleTest extends TestCase
{
    use RefreshDatabase;

    private $title;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('retire-title');

        $this->title = factory(Title::class)->states('inactive')->create();
    }

    /** @test */
    public function users_who_have_permission_can_retire_an_inactive_title()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('inactive-titles.index'))
                        ->post(route('titles.retire', $this->title));

        $response->assertStatus(302);
        $response->assertRedirect(route('inactive-titles.index'));
        $this->assertNotNull($this->title->retirements->first()->retired_at);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_retire_an_inactive_title()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('inactive-titles.index'))
                        ->post(route('titles.retire', $this->title));

        $response->assertStatus(403);
        $this->assertTrue($this->title->retirements->isEmpty());
    }

    /** @test */
    public function guests_cannot_retire_a_title()
    {
        $response = $this->from(route('inactive-titles.index'))
                        ->post(route('titles.retire', $this->title));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
