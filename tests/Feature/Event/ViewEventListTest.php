<?php

namespace Tests\Feature\Event;

use Tests\TestCase;
use App\Models\Event;
use Carbon\Carbon;
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
    public function users_who_have_permission_can_view_the_list_of_scheduled_events()
    {
        $eventA = factory(Event::class)->create(['date' => Carbon::tomorrow()]);
        $eventB = factory(Event::class)->create(['date' => Carbon::today()->addWeeks(2)]);
        $eventC = factory(Event::class)->create(['date' => Carbon::today()->subWeeks(2)]);

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('events.index'));

        $response->assertStatus(200);
        $response->assertViewIs('events.index');
        $response->data('scheduledEvents')->assertContains($eventA);
        $response->data('scheduledEvents')->assertContains($eventB);
        $response->data('scheduledEvents')->assertNotContains($eventC);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_previous_events()
    {
        $this->withoutExceptionHandling();
        $eventA = factory(Event::class)->create(['date' => Carbon::today()->subWeeks(2)]);
        $eventB = factory(Event::class)->create(['date' => Carbon::today()->subWeeks(2)]);
        $eventC = factory(Event::class)->create(['date' => Carbon::today()]);

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('events.index'));

        $response->assertStatus(200);
        $response->assertViewIs('events.index');
        $response->data('previousEvents')->assertContains($eventA);
        $response->data('previousEvents')->assertContains($eventB);
        $response->data('previousEvents')->assertNotContains($eventC);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_archived_events()
    {
        $this->withoutExceptionHandling();
        $eventA = factory(Event::class)->create(['date' => Carbon::today()->yesterday(), 'archived_at' => Carbon::now()]);
        $eventB = factory(Event::class)->create(['date' => Carbon::today()->subWeeks(2), 'archived_at' => Carbon::yesterday()]);
        $eventC = factory(Event::class)->create(['date' => Carbon::today()]);

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('events.index'));

        $response->assertStatus(200);
        $response->assertViewIs('events.index');
        $response->data('archivedEvents')->assertContains($eventA);
        $response->data('archivedEvents')->assertContains($eventB);
        $response->data('archivedEvents')->assertNotContains($eventC);
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
