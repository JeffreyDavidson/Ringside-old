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
    public $wrestlers;
    public $titles;
    public $referees;

    public function __construct()
    {
        $this->wrestlers = collect();
        $this->titles = collect();
        $this->referees = collect();
    }

    public function create()
    {
        $match = factory(Match::class)->create([
            'event_id' => $this->event_id ?? factory(Event::class)->create(),
            'match_type_id' => $this->match_type_id ?? factory(MatchType::class)->create(),
            'stipulation_id' => $this->stipulation_id ?? null,
        ]);

        // I need to make sure that the amount of wrestlers that are added to
        // the match are of the match types total competitors value and the
        // wrestlers must have be hired before the date of the event that
        // match belongs to. I will also need to split the collection
        // of wrestlers because its easier to split the wrestlers based
        // on the match types number of sides value for that type.
        // Then I need to add the collection of wrestlers to
        // the match.

        // If I call the withWrestler method I want to make sure that the
        // wrstler passed into the method is INCLUDED into the match.
        // dd($this->wrestlers);
        if ($this->wrestlers->isEmpty()) {
            // dd('is empty');
            $numWrestlersToAddToMatch = $match->type->total_competitors;
            $wrestlersForMatch = factory(Wrestler::class, (int) $numWrestlersToAddToMatch)->create(['hired_at' => $match->date->copy()->subMonths(2)]);
            $concatenatedWrestlers = $this->wrestlers->push($wrestlersForMatch);
            $this->wrestlers = $concatenatedWrestlers;
        } else {
            $numWrestlersToAddToMatch = $match->type->total_competitors - $this->wrestlers->count();
            $wrestlersForMatch = factory(Wrestler::class, (int) $numWrestlersToAddToMatch)->create(['hired_at' => $match->date->copy()->subMonths(2)]);
            $concatenatedWrestlers = $this->wrestlers->push($wrestlersForMatch);
            $this->wrestlers = $concatenatedWrestlers;
        }
        // dd($this->wrestlers);
        // dd($wrestlersForMatch);
        $splitWrestlers = $this->wrestlers->split($match->type->number_of_sides);

        $match->addWrestlers($splitWrestlers);

        // if (! is_null($this->titles)) {
        //     $match->addTitles($this->titles);
        // }

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

    public function withWrestler(Wrestler $wrestler)
    {
        $concatenated = $this->wrestlers->push([$wrestler]);

        $this->wrestlers = $concatenated;

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
            $refereesForMatch = $refereesForMatch->push(factory(Referee::class, $refereesToCreate)->create());
        }

        $match->addReferees($refereesForMatch);
    }
}
