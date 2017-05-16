<?php

use App\Models\Arena;
use App\Models\Event;
use App\Models\Stipulation;
use App\Models\Title;
use App\Models\Referee;
use App\Models\Wrestler;
use Carbon\Carbon;
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
		//perform check on the number of wrestlers hired before date.
		//if more than one create event with at most 10 matches
		//if 1 or less, go to next date
		$lastDate = Carbon::parse('January 9, 1980');
		$i = 0;
		while($lastDate->lt(Carbon::now()->subDay(14))) {
			$event = factory(Event::class)->create([
				'name' => 'Event ' . $i++,
				'slug' => 'event' . $i,
				'arena_id' => Arena::inRandomOrder()->first()->id,
				'date' => $lastDate = $lastDate->addDay(7)
			]);

			for($j = 1; $j <= 8; $j++) {
				$event->matches()->save($match = factory(App\Models\
                Match::class)->create([
					'match_number'  => $j,
					'match_type_id' => 1,
				]));

				if($this->chance(5)) {
					$wrestler = '';
					$wrestler2 = '';
					$match->addTitles($title = Title::valid($event->date)->get()->random());

					//Add first Wrestler
					$match->addWrestler($wrestler = $title->getCurrentChampion() ?: Wrestler::where('hired_at', '<', $event->date)->get()->random());

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
							$wrestler2 = $title2->getCurrentChampion() ?: Wrestler::where('hired_at', '<', $event->date)->whereNotIn('id', [$match->wrestlers->first()->id])->get()->random();
							$excludes->push($title2->id);
						} while ( $wrestler->id === $wrestler2->id );

						if($title2) {
							$match->addTitles($title2);
						} else {
							$wrestler2 = Wrestler::where('hired_at', '<', $event->date)->whereNotIn('id', [$match->wrestlers->first()->id])->get()->random();
						}
					} else {
						$wrestler2 = Wrestler::where('hired_at', '<', $event->date)->whereNotIn('id', [$match->wrestlers->first()->id])->get()->random();
					}

					$match->addWrestler($wrestler2);
				} else {
					$match->addWrestler(Wrestler::where('hired_at', '<', $event->date)->get()->random());
					$match->addWrestler(Wrestler::where('hired_at', '<', $event->date)->get()->except($match->wrestlers->first()->id)->random());
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
}