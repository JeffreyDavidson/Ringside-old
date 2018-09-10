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
use Illuminate\Database\Eloquent\Collection;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $start = Carbon::parse('First Monday of January 2010');
        $nextMonth = Carbon::now()->addMonth();

        // Gather dates for Mondays, Thursdays, and Sundays from the given start date
        // up until the the current date + 1 month. Then for each of those dates up until
        // the current date + 2 weeks create an event with matches.
        collect([
            'monday' => false,
            'thursday' => false,
            'sunday' => true,
        ])->flatMap(function ($bool, $day) use ($start, $nextMonth) {
            return $this->dates($start, $nextMonth, $day, $bool);
        })->sort(function ($a, $b) {
            return strtotime($a) - strtotime($b);
        })->values()->map(function ($date, $key) {
            return factory(Event::class)->create([
                'name' => 'Event '.($key + 1),
                'slug' => 'event'.($key + 1),
                'venue_id' => Venue::inRandomOrder()->first()->id,
                'date' => $date->hour(19),
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
                'match_decision_id' => MatchDecision::inRandomOrder()->first()->id,
            ]));

            $this->addReferees($match);
            $this->addStipulation($match);
            $this->addTitles($match);
            $this->addWrestlers($match);
            $this->setWinnersAndLosers($match);
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
        $champions = collect();

        if ($match->isTitleMatch()) {
            $champions = $match->titles->map(function ($title) {
                return optional($title->currentChampion)->wrestler;
            })->filter();
        }

        $availableWrestlers = Wrestler::inRandomOrder()
                                    ->hiredBefore($match->date)
                                    ->whereNotIn('id', $champions->pluck('id')->all())
                                    ->get();

        $expectedWrestlersCount = ($match->type->total_competitors ?? rand(5, max(5, $availableWrestlers->count())) - $champions->count());

        $wrestlersForMatch = $availableWrestlers->take($expectedWrestlersCount);

        $wrestlersForMatch = $wrestlersForMatch->concat($champions);

        if (is_null($match->type->number_of_sides)) {
            $wrestlersForMatch = $wrestlersForMatch->split($wrestlersForMatch->count());
        } else {
            $wrestlersForMatch = $wrestlersForMatch->split($match->type->number_of_sides);
        }

        return $match->addWrestlers($wrestlersForMatch);
    }

    public function setWinnersAndLosers($match)
    {
        // If this is a title match give the champion a 10% chance to retain their title.
        if ($match->isTitleMatch()) {
            $champions = $match->titles->pluck('currentChampion.wrestler')->filter();
            if ($champions->isEmpty()) {
                // No current Champion
                $groupedWrestlersBySides = $match->wrestlers->groupBy('pivot.side_number');
                $winningSideKey = $groupedWrestlersBySides->keys()->random();
                $winners = $groupedWrestlersBySides->get($winningSideKey);
                $groupedWrestlersBySides->forget($winningSideKey);

                $match->setWinners($winners);
                $match->setLosers($groupedWrestlersBySides);

                return;
            } elseif ($this->chance(10)) {
                // Champion retained their title
                $match->setWinners($champions);
                $groupedWrestlersBySides = $match->wrestlers->groupBy('pivot.side_number');
                $losingSides = $groupedWrestlersBySides->reject(function (Collection $side) use ($champions) {
                    return $side->has($champions->first()->id);
                });
                $match->setLosers($losingSides);

                return;
            } else {
                // Champion lost the title
                $groupedWrestlersBySides = $match->wrestlers->groupBy('pivot.side_number');
                $winningSideKey = $groupedWrestlersBySides->reject(function (Collection $side) use ($champions) {
                    return $side->has($champions->first()->id);
                })->keys()->random();
                $match->setWinners($groupedWrestlersBySides->pull($winningSideKey)); // pull = remove the item from the collection, and return it
                $match->setLosers($groupedWrestlersBySides);

                return;
            }
        }

        $groupedWrestlersBySides = $match->wrestlers->groupBy('pivot.side_number');
        $winningSideKey = $groupedWrestlersBySides->keys()->random();
        $winners = $groupedWrestlersBySides->get($winningSideKey);
        $groupedWrestlersBySides->forget($winningSideKey);

        $match->setWinners($winners);
        $match->setLosers($groupedWrestlersBySides);
    }

    private function dates(Carbon $from, Carbon $to, $day, $last = false)
    {
        $step = $from->copy()->startOfMonth();
        $modification = sprintf($last ? 'last %s of next month' : 'next %s', $day);

        $dates = [];
        while ($step->modify($modification)->lte($to)) {
            $dates[$step->timestamp] = $step->copy();
        }

        return $dates;
    }

    private function chance(int $percent)
    {
        return rand(0, 100) < $percent;
    }
}
