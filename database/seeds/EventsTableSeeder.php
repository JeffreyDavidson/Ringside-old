<?php

use App\Event;
use App\Stipulation;
use App\Title;
use App\Referee;
use App\Wrestler;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$lastDate = Carbon::parse('January 2, 1970');
        for($i = 1; $i <= 500; $i++) {
            $event = factory(Event::class)->create([
            	'name' => 'Event '.$i,
				'slug' => 'event'.$i,
				'arena_id' => \App\Arena::inRandomOrder()->first()->id,
				'date' => $lastDate = Carbon::createFromTimestamp($this->getEventDate($lastDate->timestamp, Carbon::now()->timestamp))
			]);

            for($j = 1; $j <= 8; $j++) {
                $event->matches()->save($match = factory(App\Match::class)->create([
                    'match_number'  => $j,
                    'match_type_id' => 1,
                ]));

				if($this->chance(5)) {
					$match->addTitles($title = Title::valid($event->date)->get()->random());
					if($this->chance(1)) {
						$title2 = Title::valid($event->date)->get()->except($title->id);
						if($title->getCurrentChampion()) {
							$excludes = $title->getCurrentChampion()->titles()->whereNull('lost_on')->get()->map(function($item) {
								return $item->id;
							});
							$title2->except($excludes->count() == 1 ? $excludes->first() : $excludes);
						}

						$match->addTitles($title2 = $title2->random());
					}
				}

				if(isset($title)) {
					$match->addWrestler($wrestler = ($title->getCurrentChampion() ?: Wrestler::inRandomOrder()->first()));
					if(isset($title2)) {
						$match->addWrestler($title2->getCurrentChampion() ?: Wrestler::get()->except($wrestler->id)->random());
					} else {
						$match->addWrestler(Wrestler::get()->except($wrestler->id)->random());
					}
				} else {
					$match->addWrestler(Wrestler::get()->random());
					$match->addWrestler(Wrestler::get()->except($match->wrestlers->first()->id)->random());
				}

				$match->load('wrestlers');
				$wrestlers = $match->wrestlers;
                $match->setWinner($wrestlers->random());

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

    /*
     * Helpers
     */

    public function chance(int $percent) {
        return rand(0,100) < $percent;
    }

	public function getEventDate(int $min, int $max) {
    	$spread = $max - $min;

		$result = mt_rand($min, $max - ($spread * (90 / 100)));

		return $result;
    }
}