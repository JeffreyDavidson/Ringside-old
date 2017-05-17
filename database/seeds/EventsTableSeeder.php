<?php

use App\Models\Arena;
use App\Models\Event;
use App\Models\Match;
use App\Models\Stipulation;
use App\Models\Title;
use App\Models\Referee;
use App\Models\Wrestler;
use App\Models\WrestlerBio;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    protected $wrestlerCount = 0;
    protected $wrestlers;

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
        $lastDate = Carbon::parse('January 2, 2000');
        $i = 0;
        while($lastDate->lt(Carbon::now()->subDay(14))) {
            $event = factory(Event::class)->create([
                'name' => 'Event ' . ++$i,
                'slug' => 'event' . $i,
                'arena_id' => Arena::inRandomOrder()->first()->id,
                'date' => $lastDate->addDay(random_int(1,24) * 7)
            ]);

            if($i == 1) {
                $this->createWrestler($lastDate);
                $this->createWrestler($lastDate);
            } else {
                foreach(range(0,6) as $k) {
                    while ( $this->chance(15) ) {
                        $this->createWrestler($lastDate->copy()->subDay(7 - $k));
                    }
                }
            }

            $this->wrestlers = Wrestler::where('hired_at', '<', $lastDate)->active()->get();
            $matchCount = $this->wrestlers->count() < 9 ? $this->wrestlers->count() - 1 : 8;

            for($j = 1; $j <= $matchCount; $j++) {
                $event->matches()->save($match = factory(Match::class)->create([
                    'match_number'  => $j,
                    'match_type_id' => 1,
                ]));

                $this->addTitles($event, $match);

                $match->load('wrestlers');
                $wrestlers = $match->wrestlers;
                $match->setWinner($wrestlers->random());
                $this->wrestlers->push($match->getWinner());

                $this->addReferees($match);
                $this->addStipulations($match);
            }
        }
    }

    /*
     * Helpers
     */

    public function chance(int $percent) {
        return rand(0,100) < $percent;
    }

    public function createWrestler($date) {
        $wrestler = factory(Wrestler::class)->states($this->getStatus())->create([
            'name' => 'Wrestler ' . ++$this->wrestlerCount,
            'slug' => 'wrestler' . $this->wrestlerCount,
            'hired_at' => $date
        ]);

        $wrestler->bio()->save(factory(WrestlerBio::class)->create([
            'wrestler_id' => $wrestler->id,
            'signature_move' => 'Signature Move ' . $this->wrestlerCount
        ]));

        return $wrestler;
    }

    public function getStatus() {
        return collect(['active', 'active', 'active', 'active', 'active', 'active', 'inactive', 'injured', 'suspended', 'retired'])->random();
    }

    public function getWrestler($title = null) {
        if($title) {
            if($wrestler = $title->getCurrentChampion()) {
                $this->wrestlers = $this->wrestlers->filter(function($item) use($wrestler) {
                    return $item->id != $wrestler->id;
                });
                return $wrestler;
            }
        }

        return ($this->wrestlers = $this->wrestlers->shuffle())->pop();
    }

    public function addTitles($event, $match) {
        if($this->chance(5)) {
            $wrestler2 = '';
            $match->addTitles($title = Title::valid($event->date)->get()->random());

//			Add first Wrestler
            $match->addWrestler($wrestler = $this->getWrestler($title));

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
                    $wrestler2 = $this->getWrestler($title2);
                    $excludes->push($title2->id);
                } while ( $wrestler->id === $wrestler2->id );

                if($title2) {
                    $match->addTitles($title2);
                } else {
                    $wrestler2 = $this->getWrestler();
                }
            } else {
                $wrestler2 = $this->getWrestler();
            }

            $match->addWrestler($wrestler2);
        } else {
            $match->addWrestler($this->getWrestler());
            $match->addWrestler($this->getWrestler());
        }
    }

    public function addReferees($match) {
        $match->addReferees($referee = Referee::get()->random());
        if ($this->chance(1)) {
            $match->addReferees(Referee::get()->except($referee->id)->random());
        }
    }

    public function addStipulations($match) {
        if($this->chance(5)) {
            $match->addStipulations($stipulation = Stipulation::get()->random());

            if($this->chance(1)) {
                $match->addStipulations(Stipulation::get()->except($stipulation->id)->random());
            }
        }
    }
}