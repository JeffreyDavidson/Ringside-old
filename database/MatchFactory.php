<?php

use App\Models\Match;
use App\Models\Referee;
use App\Models\Wrestler;

class MatchFactory
{
    public static function create($overrides = [], $wrestlers = [], $referees = [], $titles = [])
    {
        $match = factory(Match::class)->create($overrides);

        self::addWrestlersForMatch($wrestlers, $match);

        self::addRefereesForMatch($referees, $match);

        self::addTitlesForMatch($titles, $match);

        return $match;
    }

    /**
     * @param $wrestlers
     * @param $match
     */
    public static function addWrestlersForMatch($wrestlers, $match)
    {
        $numberOfCompetitorsForMatch = $match->type->number_of_competitors;
        $numberOfWrestlersToAddToMatch = $numberOfCompetitorsForMatch - count($wrestlers);

        if (count($wrestlers) > (int) $numberOfCompetitorsForMatch) {
            throw new Exception('There are too many wrestlers trying to be added to this match.');
        }

        $match->addWrestlers($wrestlers);

        if ($numberOfWrestlersToAddToMatch > 0) {
            $wrestlersToAddToMatch = factory(Wrestler::class, $numberOfWrestlersToAddToMatch)->create();
            $match->wrestlers()->saveMany($wrestlersToAddToMatch);
        }
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

    /**
     * @param $titles
     * @param $match
     */
    public static function addTitlesForMatch($titles, $match)
    {
        $match->titles()->saveMany($titles);
    }
}
