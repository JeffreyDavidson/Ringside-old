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

    public function addTitles($match)
    {
        if (chance(5)) {
            $match->addTitle($title = Title::active($match->event->date)->inRandomOrder()->first());
        }
    }

    public function addWrestlers($match)
    {
        // If this isn't a title match we just want to add two random wrestlers
        if (! $match->isTitleMatch()) {
            return $match->addWrestlers(Wrestler::inRandomOrder()->take(2)->get());
        }

        // Otherwise, we're going to start by adding the title holder(s)
        $wrestlers = $match->titles->map(function ($title) {
            return optional($title->currentChampion)->wrestler;
        })->filter();

        // If we haven't returned any wrestlers, we want to add two random
        // wrestlers. If we did, we only want to add one — but we ought
        // to ensure that we don't add one who has already been added.
        $randoms = Wrestler::inRandomOrder()
            ->whereNotIn('id', $wrestlers->pluck('id')->all())
            ->take($wrestlers->count() ? 1 : 2)
            ->get();
        $wrestlers->merge($randoms);

        // Finally, add the wrestlers
        return $match->addWrestlers($wrestlers);
    }

    public function setWinner($match)
    {
        $match->load('wrestlers');
        // If this is a title match give the champion a 3% chance to retain their title.
        if ($match->isTitleMatch() && chance(3)) {
            $champions = $match->titles->map(function ($title) {
                return $title->currentChampion;
            })->filter();

            $match->setWinner($champions->random());
        }

        // Otherwise just choose a random winner.
        $match->setWinner($match->wrestlers->random());
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

    function chance(int $percent)
    {
        return rand(0, 100) < $percent;
    }
}
