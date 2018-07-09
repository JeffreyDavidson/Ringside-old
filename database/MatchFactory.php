<?php

use App\Models\Event;
use App\Models\Match;
use App\Models\Referee;
use App\Models\Wrestler;
use App\Models\MatchType;

class MatchFactory
{
    public $event_id = null;
    public $match_type_id = null;
    public $stipulation_id = null;
    public $champion = null;
    public $titles = null;

    public function create()
    {
        $match = factory(Match::class)->create([
            'event_id' => $this->event_id ?? factory(Event::class)->create(),
            'match_type_id' => $this->match_type_id ?? factory(MatchType::class)->create(),
            'stipulation_id' => $this->stipulation_id ?? null,
        ]);

        $wrestlers = factory(Wrestler::class, (int) $match->type->total_competitors)->create(['hired_at' => $match->date->copy()->subMonths(2)]);
        $wrestlersForMatch = $wrestlers->split($match->type->number_of_sides);

        $match->addWrestlers($wrestlersForMatch);

        if (! is_null($this->titles)) {
            $match->addTitles($this->titles);
        }

        return $match;
    }

    public function forEvent(Event $event)
    {
        $this->event_id = $event->id;

        return $this;
    }

    public function forMatchNumber($matchNumber)
    {
        $this->match_number = $match_number;

        return $this;
    }

    public function withMatchType(MatchType $matchtype)
    {
        $this->match_type_id = $matchtype->id;

        return $this;
    }

    public function withStipulation(Stipulation $stipulation)
    {
        $this->stipulation_id = $stipulation->id;

        return $this;
    }

    public function withTitle($titles)
    {
        $this->titles = $titles;

        return $this;
    }

    public function withWrestlers($wrestlers)
    {
        $this->wrestlers = $wrestlers;

        return $this;
    }

    public function withChampion()
    {
        // // How many days between two dates
        // $diffInDays = $sixMonthsAgo->diffInDays($hiredOn);

        // // Get a random number in the range of $diffInDays
        // $randomDays = rand(0, $diffInDays);

        // //add that many days to $hiredOn
        // $randomDate = $hiredOn->addDays($randomDays);

        // return $this;
    }

    /**
     * @param $referees
     * @param $match
     */
    public static function addRefereesForMatch($referees, $match)
    {
        $refereesForMatch = collect($referees);
        // Check to see if we are trying to add multiple referees for a match that doesn't need multiple referees.

        $requiredReferees = $match->type->needsMultipleReferees() ? 2 : 1;
        $refereesToCreate = $requiredReferees - $refereesForMatch->count();
        if ($refereesForMatch->count() >= $requiredReferees) {
            throw new Exception('Too many referees trying to be adding to a match.');
        }

        if ($refereesToCreate) {
            $refereesForMatch = $refereesForMatch->concat(factory(Referee::class, $refereesToCreate)->create());
        }

        $match->addReferees($refereesForMatch);
    }
}
