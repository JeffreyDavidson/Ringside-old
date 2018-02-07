<?php

namespace Tests\Feature;

use EventFactory;
use MatchFactory;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Title;
use App\Models\Manager;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewWrestlerBioTest extends TestCase
{
    use DatabaseMigrations;

    private $wrestler;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('show-wrestler');

        $this->wrestler = factory(Wrestler::class)->create([
            'name' => 'Wrestler 1',
            'slug' => 'wrestler1',
            'hired_at' => '2017-08-04',
            'hometown' => 'Kansas City, Missouri',
            'height' => 73,
            'weight' => 251,
            'signature_move' => 'Powerbomb',
        ]);
    }

    /** @test */
    public function users_who_have_permission_can_view_a_wrestler_bio()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_a_wrestler_bio()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_a_wrestler_bio()
    {
        $response = $this->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function users_who_have_permission_can_view_bio_information_on_wrestler_bio()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertSee('Wrestler 1');
        $response->assertSee('Kansas City, Missouri');
        $response->assertSee('6\'1"');
        $response->assertSee('251 lbs.');
        $response->assertSee('Powerbomb');
    }

    /** @test */
    public function users_who_have_permission_can_view_list_of_current_managers_on_wrestler_bio()
    {
        $managerA = factory(Manager::class)->create(['first_name' => 'Jane', 'last_name' => 'Doe']);

        $this->wrestler->hireManager($managerA, Carbon::parse('last week'));

        $response = $this->actingAs($this->authorizedUser)->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertStatus(200);
        $response->assertSee('Jane Doe');
    }

    /** @test */
    public function users_who_have_permission_can_view_list_of_past_managers_on_wrestler_bio()
    {
        $managerA = factory(Manager::class)->create(['first_name' => 'John', 'last_name' => 'Smith']);

        $this->wrestler->hireManager($managerA, Carbon::parse('last week'));
        $this->wrestler->fireManager($managerA, Carbon::parse('yesterday'));

        $response = $this->actingAs($this->authorizedUser)          
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertStatus(200);
        $response->assertSee('John Smith');
    }

    /** @test */
    public function users_who_have_permission_can_view_list_of_current_titles_held_on_wrestler_bio()
    {
        $title = factory(Title::class)->create(['name' => 'Title A']);

        $this->wrestler->winTitle($title, Carbon::yesterday());

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertSee('Title A');
    }

    /** @test */
    public function users_who_have_permission_can_view_list_of_past_titles_held_on_wrestler_bio()
    {
        $title = factory(Title::class)->create(['name' => 'Title A']);

        $this->wrestler->winTitle($title, Carbon::parse('last week'));
        $this->wrestler->loseTitle($title, Carbon::yesterday());

        $response = $this->actingAs($this->authorizedUser)          
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertSee('Title A');
    }

    /** @test */
    public function users_who_have_permission_can_view_list_of_currently_scheduled_matches_on_wrestler_bio()
    {
        $event = EventFactory::create(['name' => 'Event Name', 'date' => Carbon::parse('tomorrow')]);
        $match = MatchFactory::create(['event_id' => $event->id], [$this->wrestler]);

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertSee('Event Name');
    }

    /** @test */
    public function users_who_have_permission_can_view_list_of_past_matches_on_wrestler_bio()
    {
        $event = EventFactory::create(['name' => 'Event Name', 'date' => Carbon::now()->subMonth()]);
        $match = MatchFactory::create(['event_id' => $event->id], [$this->wrestler]);

        $response = $this->actingAs($this->authorizedUser)          
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertSee('Event Name');
    }
}
