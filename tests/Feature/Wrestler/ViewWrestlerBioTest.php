<?php

namespace Tests\Feature\Wrestler;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Wrestler;
use MatchFactory;
use TitleFactory;
use ManagerFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewWrestlerBioTest extends TestCase
{
    use RefreshDatabase;

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

        $response->assertSuccessful();
        $response->assertViewIs('wrestlers.show');
        $response->assertViewHas('wrestler');
        $this->assertTrue($response->data('wrestler')->is($this->wrestler));

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
    public function wrestlers_bio_information_can_be_viewed_on_wrestler_bio()
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
    public function wrestlers_current_managers_can_be_viewed_on_wrestler_bio()
    {
        $currentManagerA = ManagerFactory::createHiredTimeForWrestlerBetweenDates($this->wrestler, Carbon::today()->subMonths(5), NULL);
        $currentManagerB = ManagerFactory::createHiredTimeForWrestlerBetweenDates($this->wrestler, Carbon::today()->subMonths(2), NULL);
        $pastManager = ManagerFactory::createHiredTimeForWrestlerBetweenDates($this->wrestler, Carbon::today()->subWeeks(2), Carbon::today());

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->data('wrestler')->currentManagers->assertContains($currentManagerA);
        $response->data('wrestler')->currentManagers->assertContains($currentManagerB);
        $response->data('wrestler')->currentManagers->assertNotContains($pastManager);
    }

    /** @test */
    public function wrestlers_past_managers_can_be_viewed_on_wrestler_bio()
    {
        $pastManagerA = ManagerFactory::createHiredTimeForWrestlerBetweenDates($this->wrestler, Carbon::today()->subMonths(5), Carbon::today()->subMonths(3));
        $pastManagerB = ManagerFactory::createHiredTimeForWrestlerBetweenDates($this->wrestler, Carbon::today()->subMonths(2), Carbon::today()->subWeeks(3));
        $currentManager = ManagerFactory::createHiredTimeForWrestlerBetweenDates($this->wrestler, Carbon::yesterday(), NULL);

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->data('wrestler')->pastManagers->assertContains($pastManagerA);
        $response->data('wrestler')->pastManagers->assertContains($pastManagerA);
        $response->data('wrestler')->pastManagers->assertNotContains($currentManager);
    }

    /** @test */
    public function wrestlers_current_titles_held_can_be_viewed_on_wrestler_bio()
    {
        $currentTitleA = TitleFactory::createReignForWrestlerBetweenDates($this->wrestler, Carbon::today()->subMonths(2), NULL);
        $currentTitleB = TitleFactory::createReignForWrestlerBetweenDates($this->wrestler, Carbon::yesterday(), NULL);
        $pastTitle = TitleFactory::createReignForWrestlerBetweenDates($this->wrestler, Carbon::today()->subDays(4), Carbon::yesterday());

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        dd($response->data('wrestler')->currentTitlesHeld);
        $response->data('wrestler')->currentTitlesHeld->assertContains($currentTitleA);
        $response->data('wrestler')->currentTitlesHeld->assertContains($currentTitleB);
        $response->data('wrestler')->currentTitlesHeld->assertNotContains($pastTitle);
    }

    /** @test */
    public function wrestlers_past_titles_held_can_be_viewed_on_wrestler_bio()
    {
        $pastTitleA = TitleFactory::createReignForWrestlerBetweenDates($this->wrestler, Carbon::today()->subMonths(2), Carbon::today()->subMonths(1));
        $pastTitleB = TitleFactory::createReignForWrestlerBetweenDates($this->wrestler, Carbon::today()->subWeeks(3), Carbon::today()->subWeeks(2));
        $currentTitle = TitleFactory::createReignForWrestlerBetweenDates($this->wrestler, Carbon::yesterday(), NULL);

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.show', $this->wrestler->id));

                        dd($response->data('wrestler')->pastTitlesHeld);
        $response->data('wrestler')->pastTitlesHeld->assertContains($pastTitleA);
        $response->data('wrestler')->pastTitlesHeld->assertContains($pastTitleB);
        $response->data('wrestler')->pastTitlesHeld->assertNotContains($currentTitle);
    }

    /** @test */
    public function wrestlers_currently_scheduled_matches_can_be_viewed_on_wrestler_bio()
    {
        $scheduledMatchA = MatchFactory::createForWrestlerOnDate($this->wrestler, Carbon::tomorrow());
        $scheduledMatchB = MatchFactory::createForWrestlerOnDate($this->wrestler, Carbon::today());
        $pastMatch = MatchFactory::createForWrestlerOnDate($this->wrestler, Carbon::today()->subWeeks(2));

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->data('wrestler')->scheduledMatches->assertContains($scheduledMatchA);
        $response->data('wrestler')->scheduledMatches->assertContains($scheduledMatchB);
        $response->data('wrestler')->scheduledMatches->assertNotContains($pastMatch);
    }

    /** @test */
    public function wrestlers_past_matches_can_be_viewed_on_wrestler_bio()
    {
        $pastMatchA = MatchFactory::createForWrestlerOnDate($this->wrestler, Carbon::yesterday());
        $pastMatchB = MatchFactory::createForWrestlerOnDate($this->wrestler, Carbon::today()->subWeeks(2));
        $scheduledMatch = MatchFactory::createForWrestlerOnDate($this->wrestler, Carbon::today());

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->data('wrestler')->pastMatches->assertContains($pastMatchA);
        $response->data('wrestler')->pastMatches->assertContains($pastMatchB);
        $response->data('wrestler')->pastMatches->assertNotContains($scheduledMatch);
    }
}
