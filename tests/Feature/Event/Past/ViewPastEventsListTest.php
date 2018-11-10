<?php

namespace Tests\Feature\Event\Past;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        $this->withoutExceptionHandling();
        $pastEventA = factory(Event::class)->states('past')->create();
        $pastEventB = factory(Event::class)->states('past')->create();
        $scheduledEvent = factory(Event::class)->states('scheduled')->create();
        $archivedEvent = factory(Event::class)->states('archived')->create();

        $response = $this->actingAs($this->authorizedUser)
            ->get(route('past-events.index'));

        $response->assertSuccessful();
        $response->assertViewIs('events.past');
        $response->assertViewHas('pastEvents');
        $response->data('pastEvents')->assertEquals([
            $pastEventA,
            $pastEventB,
        ]);
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
