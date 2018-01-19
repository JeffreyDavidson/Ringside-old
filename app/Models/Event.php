<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use Presentable, SoftDeletes;

    /**
     * Assign which presenter to be used for model.
     *
     * @var array
     */
    protected $with = ['matches', 'venue'];

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\EventPresenter';

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date'];

    /**
     * An event has many matches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matches()
    {
        return $this->hasMany(Match::class)->with('type', 'referees', 'stipulations', 'wrestlers', 'titles');
    }

    /**
     * An event belongs to one venue.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class)->withTrashed();
    }

    /**
     * Retrieves the past events.
     *
     * @return collection
     */
    public function isPast()
    {
        return $this->date->isPast();
    }

    /**
     * Set the date field for the event.
     *
     * @return date
     */
    public function setDateAttribute($date)
    {
        return $this->attributes['date'] = $date;
    }

    /**
     * The last match of an event is the main event.
     *
     * @return object
     */
    public function mainEvent()
    {
        return $this->matches->last();
    }

    public function addMatch($match)
    {
        $this->matches()->create($match);
    }
}
