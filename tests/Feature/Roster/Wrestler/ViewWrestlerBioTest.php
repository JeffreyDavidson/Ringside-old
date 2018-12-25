<?php

namespace Tests\Feature\Roster\Wrestler;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Title;
use App\Models\Manager;
use Facades\MatchFactory;
use Facades\ManagerFactory;
use Tests\IntegrationTestCase;
use App\Models\Roster\Wrestler;
use Facades\ChampionshipFactory;
use Facades\CompetitorHiringManagerFactory;

class ViewWrestlerBioTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-roster-member');
    }

    /** @test */
    public function users_who_have_permission_can_view_a_wrestler_bio()
    {
        $this->withoutExceptionHandling();
        $wrestler = factory(Wrestler::class)->create([
            'name' => 'Wrestler 1',
            'slug' => 'wrestler1',
            'hired_at' => '2017-08-04',
            'hometown' => 'Kansas City, Missouri',
            'height' => 73,
            'weight' => 251,
            'signature_move' => 'Powerbomb',
        ]);

        $response = $this->actingAs($this->authorizedUser)->get(route('wrestlers.show', $wrestler->id));

        $response->assertSuccessful();
        $response->assertViewIs('wrestlers.show');
        $response->assertViewHas('wrestler', function ($viewWrestler) use ($wrestler) {
            return $viewWrestler->id === $wrestler->id;
        });
        $response->assertSee('Wrestler 1');
        $response->assertSee('Kansas City, Missouri');
        $response->assertSee(e('6\'1"'));
        $response->assertSee('251 lbs.');
        $response->assertSee('Powerbomb');
    }

    /** @test */
    public function a_wrestlers_past_titles_can_be_viewed_on_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->create();
        $titleA = factory(Title::class)->create(['name' => 'Past Title A']);
        $titleB = factory(Title::class)->create(['name' => 'Past Title B']);

        ChampionshipFactory::forWrestler($wrestler)->forTitle($titleA)->wonOn(Carbon::today()->subMonths(8))->lostOn(Carbon::today()->subMonths(7))->create();
        ChampionshipFactory::forWrestler($wrestler)->forTitle($titleB)->wonOn(Carbon::today()->subMonths(6))->lostOn(Carbon::today()->subMonths(4))->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('wrestlers.show', $wrestler->id));

        $response->assertSee('Past Title A');
        $response->assertSee('Past Title B');
    }

    /** @test */
    public function a_wrestlers_current_titles_can_be_viewed_on_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->create();
        $titleA = factory(Title::class)->create(['name' => 'Current Title A']);
        $titleB = factory(Title::class)->create(['name' => 'Current Title B']);

        ChampionshipFactory::forWrestler($wrestler)->forTitle($titleA)->wonOn(Carbon::today()->subMonths(8))->create();
        ChampionshipFactory::forWrestler($wrestler)->forTitle($titleB)->wonOn(Carbon::today()->subMonths(6))->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('wrestlers.show', $wrestler->id));

        $response->assertSee('Current Title A');
        $response->assertSee('Current Title B');
    }

    /** @test */
    public function a_wrestlers_past_managers_can_be_viewed_on_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->create();
        $managerA = factory(Manager::class)->create(['first_name' => 'Zoey', 'last_name' => 'Scott']);
        $managerB = factory(Manager::class)->create(['first_name' => 'Will', 'last_name' => 'Stevens']);

        CompetitorHiringManagerFactory::withCompetitor($wrestler)->hiringManager($managerA)->hiredOn(Carbon::today()->subMonths(8))->firedOn(Carbon::today()->subMonths(7))->create();
        CompetitorHiringManagerFactory::withCompetitor($wrestler)->hiringManager($managerB)->hiredOn(Carbon::today()->subMonths(6))->firedOn(Carbon::today()->subMonths(4))->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('wrestlers.show', $wrestler->id));

        $response->assertSee('Past Manager A');
        $response->assertSee('Past Manager B');
    }

    /** @test */
    public function a_wrestlers_current_managers_can_be_viewed_on_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->create();
        $managerA = factory(Manager::class)->create(['first_name' => 'Jane', 'last_name' => 'Smith']);
        $managerB = factory(Manager::class)->create(['first_name' => 'John', 'last_name' => 'Williams']);

        CompetitorHiringManagerFactory::withCompetitor($wrestler)->hiringManager($managerA)->hiredOn(Carbon::today()->subMonths(8))->create();
        CompetitorHiringManagerFactory::withCompetitor($wrestler)->hiringManager($managerB)->hiredOn(Carbon::today()->subMonths(6))->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('wrestlers.show', $wrestler->id));

        $response->assertSee('Jane Smith');
        $response->assertSee('John Williams');
    }

    /** @test */
    public function a_wrestlers_past_matches_can_be_viewed_on_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->create();
        $eventA = factory(Event::class)->states('past')->create(['name' => 'Past Event A']);
        $eventB = factory(Event::class)->states('past')->create(['name' => 'Past Event B']);
        $eventC = factory(Event::class)->states('scheduled')->create(['name' => 'Scheduled Event C']);

        MatchFactory::forEvent($eventA)->withWrestler($wrestler)->create();
        MatchFactory::forEvent($eventB)->withWrestler($wrestler)->create();
        MatchFactory::forEvent($eventB)->withWrestler($wrestler)->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('wrestlers.show', $wrestler->id));

        $response->assertSee('Past Event A');
        $response->assertSee('Past Event B');
        $response->assertDontSee('Scheduled Event C');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_a_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->get(route('wrestlers.show', $wrestler->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_a_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->create();

        $response = $this->get(route('wrestlers.show', $wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
