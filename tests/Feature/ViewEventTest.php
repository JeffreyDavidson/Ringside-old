<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewEventTest extends TestCase
{
    use DatabaseMigrations;

    private $event;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('show-event');

        $this->event = factory(Event::class)->create([
            'name' => 'Event Name',
            'slug' => 'event-slug',
            'date' => '2017-09-17'
        ]);
    }

    /** @test */
    public function users_who_have_permission_can_view_a_event()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('events.show', $this->event->id));

        $response->assertSuccessful();
        $response->assertViewIs('events.show');
        $response->assertViewHas('event');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_a_event()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('events.show', $this->event->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_a_event()
    {
        $response = $this->get(route('events.show', $this->event->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
