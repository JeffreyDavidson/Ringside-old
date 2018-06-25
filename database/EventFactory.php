<?php

use App\Models\Event;
use Illuminate\Support\Collection;

class EventFactory
{
    public static function create($overrides = [], $totalNumberOfMatches = 8)
    {
        $event = factory(Event::class)->create($overrides);

        Collection::times($totalNumberOfMatches, function ($number) use ($event) {
            return MatchFactory::create(['event_id' => $event->id, 'match_number' => $number]);
        });

        return $event;
    }
}
