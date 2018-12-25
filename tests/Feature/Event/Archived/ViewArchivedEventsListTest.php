<?php

namespace Tests\Feature\Event\Archived;

use App\Models\Event;
use Tests\IntegrationTestCase;

class ViewArchivedEventsListTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-events');
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_archived_events()
    {
        $archivedEventA = factory(Event::class)->states('archived')->create();
        $archivedEventB = factory(Event::class)->states('archived')->create();
        $scheduledEvent = factory(Event::class)->states('scheduled')->create();
        $pastEvent = factory(Event::class)->states('past')->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('archived-events.index'));

        $response->assertSuccessful();
        $response->assertViewIs('events.archived');
        $response->assertViewHas('archivedEvents');
        $response->data('archivedEvents')->assertEquals([
            $archivedEventA,
            $archivedEventB,
        ]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_archived_events()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('archived-events.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_list_of_archived_events()
    {
        $response = $this->get(route('archived-events.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
