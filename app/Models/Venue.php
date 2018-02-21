<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

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
     * A venue may host many events.
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
     * Returns a collection of events before the current date.
     *
     * @return boolean
     */
    public function hasPastEvents()
    {
        return $this->pastEvents->isNotEmpty();
    }
}
