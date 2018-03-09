<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewEventListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-events');
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_events()
    {
        $eventA = factory(Event::class)->create();
        $eventB = factory(Event::class)->create();
        $eventC = factory(Event::class)->create();

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('events.index'));

        $response->assertStatus(200);
        $response->data('events')->assertEquals([
            $eventA,
            $eventB,
            $eventC,
        ]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_events()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('events.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_event_list()
    {
        $response = $this->get(route('events.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
