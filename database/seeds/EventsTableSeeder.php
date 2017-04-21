<?php

use App\Event;
use App\Stipulation;
use App\Title;
use App\Referee;
use App\TitleHistory;
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
		for($i = 1; $i <= 1000; $i++) {
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
					$wrestler = '';
					$wrestler2 = '';
					$match->addTitles($title = Title::valid($event->date)->get()->random());

					//Add first Wrestler
					$match->addWrestler($wrestler = $title->getCurrentChampion() ?: Wrestler::get()->random());

					if ( $this->chance(1) ) {
						$title2 = '';

						//Start Excludes list
						$excludes = $wrestler->titles->map(function ($item) {
							return $item->title_id;
						})->push($title->id);

						do {
							$builder = Title::valid($event->date)->whereNotIn('id', $excludes)->get();
							if($builder->count() != 0) {
								$title2 = null;
								break;
							}

							$title2 = $builder->random();
							$wrestler2 = $title2->getCurrentChampion() ?: Wrestler::whereNotIn('id', [$match->wrestlers->first()->id])->get()->random();
							$excludes->push($title2->id);
						} while ( $wrestler->id === $wrestler2->id );

						if($title2) {
							$match->addTitles($title2);
						} else {
							$wrestler2 = Wrestler::whereNotIn('id', [$match->wrestlers->first()->id])->get()->random();
						}
					} else {
						$wrestler2 = Wrestler::whereNotIn('id', [$match->wrestlers->first()->id])->get()->random();
					}

					$match->addWrestler($wrestler2);
				} else {
					$match->addWrestler(Wrestler::get()->random());
					$match->addWrestler(Wrestler::get()->except($match->wrestlers->first()->id)->random());
				}

//				if(isset($title)) {
//					$match->addWrestler($wrestler = ($title->getCurrentChampion() ?: Wrestler::inRandomOrder()->first()));
//					if(isset($title2)) {
//						$match->addWrestler($title2->getCurrentChampion() ?: Wrestler::get()->except($wrestler->id)->random());
//					} else {
//						$match->addWrestler(Wrestler::get()->except($wrestler->id)->random());
//					}
//				}

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

		$result = mt_rand($min, $max - ($spread * (990 / 1000)));

		return $result;
    }
}