<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Match;

/**
 * @mixin \Eloquent
 */
trait HasMatches
{
    /**
     * A wrestler can have many matches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function matches()
    {
        return $this->belongsToMany(Match::class);
    }

    /**
     * Retrieves the date of the model's first match.
     *
     * @return string|null
     */
    public function getFirstMatchDateAttribute()
    {
        return $this->matches()
            ->select('events.date as first_date')
            ->join('events', 'matches.event_id', '=', 'events.id')
            ->orderBy('events.date')
            ->value('first_date');
    }

    /**
     * Returns the model's past matches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pastMatches()
    {
        return $this->matches()->whereHas('event', function ($query) {
            $query->where('date', '<', Carbon::today());
        });
    }

    /**
     * Checks to see if the model has past matches.
     *
     * @return bool
     */
    public function hasPastMatches()
    {
        return $this->pastMatches->isNotEmpty();
    }

    /**
     * Returns the model's past matches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scheduledMatches()
    {
        return $this->matches()->whereHas('event', function ($query) {
            $query->where('date', '>=', Carbon::today());
        });
    }
}
