<?php

namespace Tests\Feature\Event\Scheduled;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        $scheduledEventA = factory(Event::class)->states('scheduled')->create();
        $scheduledEventB = factory(Event::class)->states('scheduled')->create();
        $pastEvent = factory(Event::class)->states('past')->create();
        $archivedEvent = factory(Event::class)->states('archived')->create();

        $response = $this->actingAs($this->authorizedUser)
            ->get(route('scheduled-events.index'));

        $response->assertSuccessful();
        $response->assertViewIs('events.scheduled');
        $response->assertViewHas('scheduledEvents');
        $response->data('scheduledEvents')->assertEquals([
            $scheduledEventA,
            $scheduledEventB,
        ]);
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
