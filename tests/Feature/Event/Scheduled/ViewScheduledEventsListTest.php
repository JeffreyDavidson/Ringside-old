<?php

namespace Tests\Feature\Event\Scheduled;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewScheduledEventsListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-events');
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_scheduled_events()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('scheduled-events.index'));

        $response->assertSuccessful();
        $response->assertViewIs('events.scheduled');
        $response->assertViewHas('events');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_scheduled_events()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('scheduled-events.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_list_of_scheduled_events()
    {
        $response = $this->get(route('scheduled-events.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
