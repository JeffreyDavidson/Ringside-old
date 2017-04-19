<?php

use App\Event;
use App\Stipulation;
use App\Title;
use App\Referee;
use App\Wrestler;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 20; $i++) {
            $event = factory(Event::class)->create(['name' => 'Event '.$i, 'slug' => 'event'.$i, 'arena_id' => \App\Arena::inRandomOrder()->first()->id]);

            for($j = 1; $j <= 8; $j++) {
                $event->matches()->save($match = factory(App\Match::class)->create([
                    'match_number'  => $j,
                    'match_type_id' => 1,
                ]));

				if($this->chance(5)) {
					$match->addTitles($title = Title::introducedBefore($event->date)->get()->random());
					if($this->chance(1)) {
						$match->addTitles($title2 = Title::introducedBefore($event->date)->get()->except($title->id)->random());
					}
				}

				if(isset($title)) {
					$match->addWrestlers($wrestler1 = ($title->getCurrentChampion() ?: Wrestler::inRandomOrder()->first()));
					if(isset($title2)) {
						$match->addWrestlers($wrestler2 = $title2->getCurrentChampion() ?: Wrestler::get()->except($wrestler1->id)->random());
					} else {
						$match->addWrestlers($wrestler2 = Wrestler::get()->except($wrestler1->id)->random());
					}
					$wrestlers = $match->wrestlers()->get();
					$match->winner($wrestler = $wrestlers->random());
				} else {
					$match->addWrestlers($wrestlers = Wrestler::get()->random(2));
					$match->winner($wrestlers->random());
				}

				$match->addReferees($referee = Referee::get()->random());
				if ($this->chance(1)) {
					$match->addReferees(Referee::get()->except($referee->id)->random());
				}

                if($this->chance(5)) {
                    $match->addStipulations($stipulation = Stipulation::get()->random());

                    if($this->chance(1)) {
                        $match->addStipulations(Stipulation::get()->except($stipulation->id)->random());
                    }
                }
            }
        }
    }

    public function chance(int $percent) {
        return rand(0,100) < $percent;
    }
}