<?php

use App\Models\MatchType;
use App\Models\Venue;
use App\Models\Event;
use App\Models\Match;
use App\Models\Stipulation;
use App\Models\Title;
use App\Models\Referee;
use App\Models\Wrestler;
use Carbon\Carbon;
use Carbon\CarbonInterval;
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
        $start = Carbon::parse('First Monday of January 2000');
        $now = Carbon::now();

        $dates = array_merge(
            $this->dates($start, $now, 'monday'),
            $this->dates($start, $now, 'thursday'),
            $this->dates($start, $now, 'sunday', true)
        );

        function date_sort($a, $b) {
            return strtotime($a) - strtotime($b);
        }

        usort($dates, "date_sort");
        $id = 0;
        $events = array_reduce($dates, function ($events, $date) use (&$id) {
            $id++;
            $event = factory(Event::class)->create([
                'name' => 'Event '.$id,
                'slug' => 'event'.$id,
                'venue_id' => Venue::inRandomOrder()->first()->id,
                'date' => $date
            ]);

            $this->addMatches($event);

            return $event;
        }, []);

    }

    /*
     * Helpers
     */

    protected function dates(Carbon $from, Carbon $to, $day, $last = false)
    {
        $step = $from->copy()->startOfMonth();
        $modification = sprintf($last ? 'last %s of next month' : 'next %s', $day);

        $dates = [];
        while ($step->modify($modification)->lte($to)) {
            if ($step->lt($from)) {
                continue;
            }

            $dates[$step->timestamp] = $step->copy();
        }

        return $dates;
    }

    public function chance(int $percent) {
        return rand(0,100) < $percent;
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

    public function addMatches($event)
    {
        $matchesCount = rand(6, 10);
        for($matchNumber = 1; $matchNumber <= $matchesCount; $matchNumber++) {
            $match = $event->matches()->save(factory(Match::class)->create([
                'match_type_id' => MatchType::inRandomOrder()->first()->id,
                'match_number' => $matchNumber,
            ]));

            $this->addReferees($match);
            $this->addStipulations($match);
            //$this->addTitles($match);
            //$match->setWinner();

        }
    }

    public function addReferees($match) {
        if ($match->needsMoreThanOneReferee()) {
            $referees = Referee::inRandomOrder()->take(4)->get();
            $match->addReferees($referees);
        } else {
            $match->addReferee(Referee::inRandomOrder()->first());
        }
    }

    public function addStipulations($match) {
        if($this->chance(3)) {
            $stipulation = Stipulation::inRandomOrder()->first();
            $match->addStipulation($stipulation);

            if($this->chance(1)) {
                $match->addStipulation(Stipulation::where('id', '!=', $stipulation->id)->inRandomOrder()->first());
            }
        }
    }
}