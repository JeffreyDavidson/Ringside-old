<?php

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Venue;
use Facades\MatchFactory;
use Illuminate\Support\Collection;

class EventFactory
{
    public $states = null;
    public $date;
    public $venue_id;
    public $matchesCount = 0;

    public function states($states)
    {
        $this->states = $states;

        return $this;
    }

    public function create()
    {
        $event = factory(Event::class)->states($this->states)->create([
            'date' => $this->date ?? Carbon::tomorrow(),
        ]);

        Collection::times($this->matchesCount, function ($number) use ($event) {
            return MatchFactory::forEvent($event)->forMatchNumber($number)->create();
        });

        return $event;
    }

    public function atVenue(Venue $venue)
    {
        $this->venue_id = $venue->id;

        return $this;
    }

    public function onDate(Carbon $date)
    {
        $this->date = $date;

        return $this;
    }

    public function withMatches($count)
    {
        $this->matchesCount = $count;

        return $this;
    }
}
