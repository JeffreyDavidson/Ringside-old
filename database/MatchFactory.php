<?php

use App\Models\Match;
use App\Models\Event;
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

    public static function createForWrestlerOnDate($wrestler, $date)
    {
        $event = factory(Event::class)->create(['date' => $date]);

        return self::create(['event_id' => $event->id], [$wrestler]);
    }

    public function createOnDate($date, $overrides, $wrestlers, $referees, $titles)
    {
        $event = factory(Event::class)->create(['date' => $date]);

        return self::create(['event_id' => $event->id], [$wrestler], $referees, $titles);
    }

    public static function createTitleMatchWithNoChampion($overrides, $title, $wrestlers)
    {
        return self::create($overrides, $wrestlers, [], $title);
    }

    public static function createTitleMatchWithChampion($overrides, $title, $wrestlers)
    {
        return self::create($overrides, $wrestlers, [], $title);
    }

    /**
     * @param $wrestlers
     * @param $match
     */
    public static function addWrestlersForMatch($wrestlers, $match)
    {
        $wrestlersForMatch = collect($wrestlers);

        $numberOfCompetitorsForMatch = $match->type->total_competitors;
        $wrestlersAlreadyInMatch = $wrestlersForMatch->count();
        $numberOfWrestlersToAddToMatch = $numberOfCompetitorsForMatch - $wrestlersAlreadyInMatch;

        if ($wrestlersAlreadyInMatch > (int) $numberOfCompetitorsForMatch) {
            throw new Exception('There are too many wrestlers trying to be added to this match.');
        }

        if ($numberOfWrestlersToAddToMatch > 0) {
            $wrestlersForMatch = $wrestlersForMatch->concat(factory(Wrestler::class, $numberOfWrestlersToAddToMatch)->create());
        }

        $wrestlersForMatch = $wrestlersForMatch->split($match->type->number_of_sides);

        $match->addWrestlers($wrestlersForMatch);
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
        $match->titles()->attach($titles);
    }
}
