<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wrestler;
use App\Models\Manager;
use App\Models\Title;
use App\Models\Match;
use App\Models\Event;
use App\Models\WrestlerBio;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewARosterMemberBioTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function view_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create(['name' => 'Wrestler 1']);

        $wrestler->bio()->create([
            'hometown' => 'Kansas City, Missouri',
            'height' => 73,
            'weight' => 251,
            'signature_move' => 'Powerbomb'
        ]);

        $response = $this->get('wrestlers/'.$wrestler->id);

        $response->assertSee('Wrestler 1');
        $response->assertSee('Kansas City, Missouri');
        $response->assertSee('6\'1"');
        $response->assertSee('251 lbs.');
        $response->assertSee('Powerbomb');
    }

    /** @test */
    public function view_list_of_managers_on_wrestler_bio()
    {
        $this->disableExceptionHandling();
        $user = factory(User::class)->create();
        $wrestler = factory(Wrestler::class)->states('active')->create();

        tap($wrestler, function($instance) {
            $instance->bio()->save(factory(WrestlerBio::class)->make());
        });

        $firedManager = factory(Manager::class)->create(['name' => 'Fired Manager']);
        $hiredManager = factory(Manager::class)->create(['name' => 'Hired Manager']);

        $wrestler->hireManager($firedManager, Carbon::now());
        $wrestler->fireManager($firedManager, Carbon::now('+1 day'));
        $wrestler->hireManager($hiredManager, Carbon::now('+2 days'));

        $response = $this->actingAs($user)->get('wrestlers/'. $wrestler->id);

        $response->assertStatus(200);

        $response->assertSee('Fired Manager');
        $response->assertSee('Hired Manager');
    }

    /** @test */
    public function view_list_of_titles_held_on_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id]));

        $title1 = factory(Title::class)->create(['name' => 'Title 1']);
        $title2 = factory(Title::class)->create(['name' => 'Title 2']);

        $wrestler->winTitle($title1, Carbon::parse('-3 days'));
        $wrestler->loseTitle($title1, Carbon::parse('-2 days'));
        $wrestler->winTitle($title1, Carbon::parse('-2 days'));
        $wrestler->loseTitle($title1, Carbon::parse('-1 day'));
        $wrestler->winTitle($title2, Carbon::parse('-1 day'));

        $wrestler->titles->map(function($i) { return $i->title->name; });

        $this->visit('wrestlers/'.$wrestler->id);

        $this->see('Title 1 (2x)');
        $this->see('Title 2');
    }

    /** @test */
    public function view_list_of_matches_on_wrestler_bio()
    {
        $event = factory(Event::class)->create(['name' => 'My Event']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $wrestler1 = factory(Wrestler::class)->create(['name' => 'Wrestler 1']);

        $wrestler1->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler1->id]));

        $wrestler2 = factory(Wrestler::class)->create(['name' => 'Wrestler 2']);

        $match->addWrestler($wrestler1);
        $match->addWrestler($wrestler2);

        $this->visit('wrestlers/'.$wrestler1->id);
        $this->see('My Event');
    }

}
