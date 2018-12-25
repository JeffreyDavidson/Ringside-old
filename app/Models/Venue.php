<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'address', 'city', 'state', 'postcode'];

    /**
     * A model can have many events.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Determines if a model has held a past event.
     *
     * @return bool
     */
    public function hasPastEvents()
    {
        return $this->pastEvents->isNotEmpty();
    }

    /**
     * Determines if a model has held a past event.
     *
     * @return bool
     */
    public function hasScheduledEvents()
    {
        return $this->scheduledEvents->isNotEmpty();
    }

    /**
     * Returns a collection of events before the current date.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pastEvents()
    {
        return $this->events()->where('date', '<', today());
    }

    /**
     * Returns a collection of events before the current date.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scheduledEvents()
    {
        return $this->events()->where('date', '>=', today());
    }
}
