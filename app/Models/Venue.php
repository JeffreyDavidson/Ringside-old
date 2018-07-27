<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use SoftDeletes;

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

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
     * Returns a collection of events before the current date.
     *
     * @return \App\Models\Venue|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pastEvents()
    {
        return $this->events()->where('date', '<', Carbon::today());
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
}
