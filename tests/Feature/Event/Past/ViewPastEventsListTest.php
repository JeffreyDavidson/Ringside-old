<?php

namespace Tests\Feature\Event\Past;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewPastEventsListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-events');
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_past_events()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('past-events.index'));

        $response->assertSuccessful();
        $response->assertViewIs('events.past');
        $response->assertViewHas('events');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_past_events()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('past-events.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_list_of_past_events()
    {
        $response = $this->get(route('past-events.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
