<?php

namespace Tests\Feature;

use App\Wrestler;
use App\Manager;
use App\Title;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewAWrestlerBioTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function view_a_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->create([
            'name' => 'Wrestler 1',
            'hometown' => 'Kansas City, Missouri',
            'height' => 73,
            'weight' => 251,
            'signature_move' => 'Powerbomb'
        ]);
        $manager1 = factory(Manager::class)->create(['name' => 'Manager 1']);
        $manager2 = factory(Manager::class)->create(['name' => 'Manager 2']);
        $manager3 = factory(Manager::class)->create(['name' => 'Manager 3']);

        $wrestler->hireManager($manager1);
        $wrestler->fireManager($manager1);
        Carbon::setTestNow(Carbon::parse('+1 day'));
        $wrestler->hireManager($manager2);
        $wrestler->hireManager($manager3);

        $title = factory(Title::class)->create([
            'name' => 'Title 1'
        ]);

        $wrestler->winTitle($title);

        $this->visit('wrestlers/'.$wrestler->id);

        $this->see('Wrestler 1');
        $this->see('Kansas City, Missouri');
        $this->see('6\'1"');
        $this->see('251 lbs.');
        $this->see('Powerbomb');
        $this->see('Manager 1');
        $this->see('Manager 2');
        $this->see('Manager 3');
        $this->see('Title 1');

        Carbon::setTestNow();
    }

}
