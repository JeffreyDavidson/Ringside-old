<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Event;

trait HasEvents
{
    /**
     * A venue can have many events.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Determines if a venue has held a past event.
     *
     * @return bool
     */
    public function hasPastEvents()
    {
        return $this->pastEvents->isNotEmpty();
    }

    /**
     * Returns a collection of events before the current date.
     *
     * @return \App\Models\Venue|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pastEvents()
    {
        return $this->events()->where('date', '<', Carbon::today());
    }
}
