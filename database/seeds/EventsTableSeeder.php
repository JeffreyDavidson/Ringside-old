<?php

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Match;
use App\Models\Title;
use App\Models\Venue;
use App\Models\Referee;
use App\Models\Wrestler;
use App\Models\MatchType;
use App\Models\Stipulation;
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
        $start = Carbon::parse('First Monday of January 1990');
        $nextMonth = Carbon::now()->addMonth();

        collect([
            'monday' => false,
            'thursday' => false,
            'sunday' => true
        ])->flatMap(function ($bool, $day) use ($start, $nextMonth) {
            return dates($start, $nextMonth, $day, $bool);
        })->sort(function ($a, $b) {
            return strtotime($a) - strtotime($b);
        })->values()->map(function ($date, $key) {
            return factory(Event::class)->create([
                'name' => 'Event '.($key + 1),
                'slug' => 'event'.($key + 1),
                'venue_id' => Venue::inRandomOrder()->first()->id,
                'date' => $date
            ]);
        })->filter(function ($event) {
            return $event->date->lt(Carbon::today());
        })->each(function ($event) {
            $this->addMatches($event);
        });
    }

    public function addTitles($match)
    {
        if (chance(5)) {
            $match->addTitle($title = Title::active($match->event->date)->inRandomOrder()->first());
        }
    }

    public function addMatches(Event $event)
    {
        $matchesCount = rand(6, 10);
        for ($matchNumber = 1; $matchNumber <= $matchesCount; $matchNumber++) {
            $match = $event->matches()->save(factory(Match::class)->create([
                'match_type_id' => MatchType::inRandomOrder()->first()->id,
                'event_id' => $event->id,
                'match_number' => $matchNumber,
            ]));

            $this->addReferees($match);
            $this->addStipulations($match);
            $this->addTitles($match);
            $this->addWrestlers($match);
            $this->setWinner($match);
        }
    }

    public function setWinner($match)
    {
        if ($match->isTitleMatch()) {
            if (chance(3)) {
                $champions = $match->titles->map(function ($title) {
                    return $title->currentChampion();
                })->filter();
                $match->setWinner($champions->random());
            }
            $match->setWinner($match->wrestlers->random());
        } else {
            $match->setWinner($match->wrestlers->random());
        }
    }

    public function addWrestlers($match)
    {
        if ($match->isTitleMatch()) {
            $champions = $match->titles->map(function ($title) {
                return $title->currentChampion();
            })->filter();

            if ($champions->count() === 1) {
                $match->addWrestler($champions->first());
                $match->addWrestler(Wrestler::inRandomOrder()->get()->reject($champions->first())->first());
            } elseif ($champions->count() > 1) {
                $match->addWrestlers($champions);
                $match->addWrestler(Wrestler::inRandomOrder()->reject($champions)->first());
            } else {
                $match->addWrestlers(Wrestler::inRandomOrder()->take(2)->get());
            }
        } else {
            $match->addWrestlers(Wrestler::inRandomOrder()->take(2)->get());
        }
    }

    public function addReferees($match)
    {
        if ($match->type->needsMultipleReferees()) {
            $referees = Referee::inRandomOrder()->take(4)->get();
            $match->addReferees($referees);
        } else {
            $match->addReferee(Referee::inRandomOrder()->first());
        }
    }

    public function addStipulations($match)
    {
        if (chance(3)) {
            $stipulation = Stipulation::inRandomOrder()->first();
            $match->addStipulation($stipulation);
        }
    }
}
