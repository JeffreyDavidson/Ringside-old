<?php

use App\Models\Match;
use App\Models\Referee;
use App\Models\Wrestler;

class MatchFactory
{
    public static function create($overrides = [], $wrestlers = [], $referees = [], $titles = [], $stipulations = [])
    {
        $match = factory(Match::class)->create($overrides);

        self::addWrestlersForMatch($wrestlers, $match);

        self::addRefereesForMatch($referees, $match);

        self::addTitlesForMatch($titles, $match);

        self::addStipulationsForMatch($stipulations, $match);

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
            // Throw exception
        }

        $match->wrestlers()->saveMany($wrestlers);

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
        if (count($referees) > 2) {
            // Throw exception
        }

        $match->referees()->saveMany($referees);

        // if ($match->needsTwoReferees()) {
        //     $numberOfRefereesToAdd = 2 - count($referees);

        //     if ($numberOfRefereesToAdd) {
        //         $refereesToAdd = factory(Referee::class, $numberOfRefereesToAdd)->create();
        //         array_push($refereesForMatch, $refereesToAdd);
        //         array_push($refereesForMatch, $referees);
        //     }
        // }

        // if (count($referees) == 0) {
        //     array_push($refereesForMatch, factory(Referee::class)->create());
        // } elseif (count($referees) == 1) {
        //     array_push($refereesForMatch, $referees);
        // }

        // $match->addReferees($refereesForMatch);
    }

    /**
     * @param $titles
     * @param $match
     */
    public static function addTitlesForMatch($titles, $match)
    {
        $match->titles()->saveMany($titles);
    }

    /**
     * @param $referees
     * @param $match
     */
    public static function addStipulationsForMatch($stipulations, $match)
    {
        $match->stipulations()->saveMany($stipulations);
    }
}
