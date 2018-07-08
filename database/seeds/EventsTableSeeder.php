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
use App\Models\MatchDecision;
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

        // Gather dates for Mondays, Thursdays, and Sundays from the given start date
        // up until the the current date + 1 month. Then for each of those dates up until
        // the current date + 2 weeks create an event with matches.
        collect([
            'monday' => false,
            'thursday' => false,
            'sunday' => true
        ])->flatMap(function ($bool, $day) use ($start, $nextMonth) {
            return $this->dates($start, $nextMonth, $day, $bool);
        })->sort(function ($a, $b) {
            return strtotime($a) - strtotime($b);
        })->values()->map(function ($date, $key) {
            return factory(Event::class)->create([
                'name' => 'Event '.($key + 1),
                'slug' => 'event'.($key + 1),
                'venue_id' => Venue::inRandomOrder()->first()->id,
                'date' => $date->hour(19)
            ]);
        })->filter(function ($event) {
            return $event->date->lte(Carbon::today()->addWeeks(2));
        })->each(function ($event) {
            $this->addMatches($event);
        });
    }

    /**
     * Genrate a random number of matches for the passed in event.
     *
     * @param App\Models\Event $event
     * @return void
     */
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
            $this->addStipulation($match);
            $this->addTitles($match);
            $this->addWrestlers($match);
            $this->setDecision($match);
            $this->setWinner($match);
        }
    }

    public function addReferees($match)
    {
        if ($match->type->needsMultipleReferees()) {
            $referees = Referee::inRandomOrder()->hiredBefore($match->date)->take(4)->get();
            $match->addReferees($referees);
        } else {
            $match->addReferee(Referee::inRandomOrder()->hiredBefore($match->date)->first());
        }
    }

    public function addStipulation($match)
    {
        if ($this->chance(3)) {
            $stipulation = Stipulation::inRandomOrder()->first();
            $match->addStipulation($stipulation);
        }
    }

    public function addTitles($match)
    {
        if ($this->chance(5)) {
            $match->addTitle($title = Title::active($match->event->date)->inRandomOrder()->first());
        }
    }

    public function addWrestlers($match)
    {
        // If Match is a title match then get all champions for the associated titles.
        if ($match->isTitleMatch()) {
            $champions = $match->titles->map(function ($title) {
                return optional($title->currentChampion)->wrestler;
            })->filter();
        }

        // Retrieve wrestlers that are not the champions and were hired before event.
        $availableWrestlers = Wrestler::inRandomOrder()
                                    ->hiredBefore($match->date)
                                    // ->when($champions->isNotEmpty(), function ($collection) use ($champions) {
                                    //     return $collection->whereNotIn('id', $champions->pluck('id')->all());
                                    // })
                                    ->get();

        // Only get enough wrestlers needed for match.
        if (! is_null($match->type->total_competitors)) {
            if ($champions->isNotEmpty()) {
                // There are champions so make sure not to include that count in how many to get.
                $wrestlersForMatch = $nonChampions->take($match->type->total_competitors - $champions->count());
            } else {
                // There are not any champions so just get enough wrestlers to add to match.
                $wrestlersForMatch = $nonChampions->take($match->type->total_competitors);
            }
        } else {
            // There is no set maximum amount for how many wrestlers can be in a match.
            $wrestlersForMatch = $nonChampions->take(rand(5, $nonChampions->count() - $champions->count()));
        }

        $wrestlersForMatch = $wrestlersForMatch->concat($champions);

        $wrestlersForMatch = $wrestlersForMatch->split($match->type->number_of_sides);

        return $match->addWrestlers($wrestlersForMatch);
    }

    public function setDecision($match)
    {
    }

    public function setWinner($match)
    {
        $match->load('wrestlers');

        // If this is a title match give the champion a 3% chance to retain their title.
        if ($match->isTitleMatch() && $this->chance(10)) {
            $champions = $match->titles->map(function ($title) {
                return optional($title->currentChampion)->wrestler;
            })->filter();

            $match->setWinner($champions->random());
        }

        // Otherwise just choose a random winner.
        $decisionSlug = MatchDecision::inRandomOrder()->first()->slug;
        $match->setWinner($match->wrestlers->random(), $decisionSlug);
    }

    protected function dates(Carbon $from, Carbon $to, $day, $last = false)
    {
        $step = $from->copy()->startOfMonth();
        $modification = sprintf($last ? 'last %s of next month' : 'next %s', $day);

        $dates = [];
        while ($step->modify($modification)->lte($to)) {
            $dates[$step->timestamp] = $step->copy();
        }

        return $dates;
    }

    protected function chance(int $percent)
    {
        return rand(0, 100) < $percent;
    }
}
