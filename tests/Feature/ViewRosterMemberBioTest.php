<?php

namespace Tests\Feature;

use App\Wrestler;
use App\Manager;
use App\Title;
use App\Match;
use App\Event;
use App\WrestlerBio;
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

        $this->visit('wrestlers/'.$wrestler->id);

        $this->see('Wrestler 1');
        $this->see('Kansas City, Missouri');
        $this->see('6\'1"');
        $this->see('251 lbs.');
        $this->see('Powerbomb');
    }

    /** @test */
    public function view_list_of_managers_on_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id]));

        $manager1 = factory(Manager::class)->create(['name' => 'Manager 1']);
        $manager2 = factory(Manager::class)->create(['name' => 'Manager 2']);

        $wrestler->hireManager($manager1);
        $wrestler->fireManager($manager1);

        Carbon::setTestNow(Carbon::parse('+1 day'));

        $wrestler->hireManager($manager2);

        $this->visit('wrestlers/'.$wrestler->id);

        $this->see('Manager 1');
        $this->see('Manager 2');

        Carbon::setTestNow();
    }

    /** @test */
    public function view_list_of_titles_held_on_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id]));

        $title1 = factory(Title::class)->create(['name' => 'Title 1']);
        $title2 = factory(Title::class)->create(['name' => 'Title 2']);

        $wrestler->winTitle($title1);
        Carbon::setTestNow(Carbon::parse('+1 day'));
        $wrestler->loseTitle($title1);
        $wrestler->winTitle($title1);
        Carbon::setTestNow(Carbon::parse('+2 day'));
        $wrestler->loseTitle($title1);
        $wrestler->winTitle($title2);
        Carbon::setTestNow();

        $this->visit('wrestlers/'.$wrestler->id);

        $this->see('Title 1 (2x)');
        $this->see('Title 2');
    }

    /** @test */
    public function view_list_of_matches_on_wrestler_bio()
    {
        $event = create(Event::class, ['name' => 'My Event']);
        $match = create(Match::class, ['event_id' => $event->id]);
        $wrestler1 = create(Wrestler::class, ['name' => 'Wrestler 1']);

        $wrestler1->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler1->id]));

        $wrestler2 = create(Wrestler::class, ['name' => 'Wrestler 2']);

        $match->addCompetitor($wrestler1);
        $match->addCompetitor($wrestler2);

        $this->visit('wrestlers/'.$wrestler1->id);
        $this->see('My Event');
    }

}
