<?php

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Venue;
use Facades\MatchFactory;
use Illuminate\Support\Collection;

class EventFactory
{
    public $states = [];
    public $date;
    public $venue_id;
    public $matchesCount = 0;
    public $name;
    public $slug;

    public function states($states)
    {
        $this->states = $states;

        return $this;
    }

    public function create()
    {
        $str = 'Example Event Name';
        $event = factory(Event::class)->states($this->states)->create([
            'name' => $this->name ?? $str,
            'slug' => $this->slug ?? str_slug($this->name),
            'date' => $this->date ?? Carbon::tomorrow(),
            'venue_id' => $this->venue_id ?? factory(Venue::class)->create()->id,
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

    public function withName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function withSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }
}
