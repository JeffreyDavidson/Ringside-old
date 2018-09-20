<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Laracodes\Presenter\Traits\Presentable;
use App\Exceptions\EventIsArchivedException;
use App\Exceptions\EventNotArchivedException;
use App\Exceptions\EventIsScheduledException;
use Illuminate\Database\Eloquent\SoftDeletes;

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'date', 'venue_id', 'archived_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime',
        'archived_at' => 'datetime',
    ];

    /**
     * An event has many matches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matches()
    {
        return $this->hasMany(Match::class);
    }

    /**
     * An event belongs to a venue.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class)->withTrashed();
    }

    /**
     * The last match of an event is the main event.
     *
     * @return \App\Models\Match
     */
    public function mainEvent()
    {
        return $this->matches()->latest('match_number')->toHasOne();
    }

    /**
     * Adds a match to the event.
     *
     * @param  \App\Models\Match  $match
     * @return void
     */
    public function addMatch(Match $match)
    {
        $this->matches()->save($match);
    }

    /**
     * Archive an event.
     *
     * @return void
     */
    public function archive()
    {
        if ($this->isArchived()) {
            throw new EventIsArchivedException;
        }

        if ($this->isScheduled()) {
            throw new EventIsScheduledException;
        }

        return $this->update(['archived_at' => now()]);
    }

    /**
     * Archive an event.
     *
     * @return void
     */
    public function unarchive()
    {
        if (!$this->isArchived()) {
            throw new EventNotArchivedException;
        }

        if ($this->isScheduled()) {
            throw new EventIsScheduledException;
        }

        return $this->update(['archived_at' => null]);
    }

    /**
     * Scope a query to only include scheduled events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeScheduled(Builder $query)
    {
        return $query->where('date', '>=', today());
    }

    /**
     * Scope a query to only include past events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePast(Builder $query)
    {
        return $query->where('date', '<', today());
    }

    /**
     * Scope a query to only include archived events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeArchived(Builder $query)
    {
        return $query->whereNotNull('archived_at');
    }

    /**
     * Checks to see if the event is scheduled for a future date.
     *
     * @return bool
     */
    public function isScheduled()
    {
        return $this->date->gte(today());
    }

    /**
     * Checks to see if the event's date has past.
     *
     * @return bool
     */
    public function isPast()
    {
        return $this->date->lt(today());
    }

    /**
     * Checks to see if the event is archived.
     *
     * @return bool
     */
    public function isArchived()
    {
        return !is_null($this->archived_at);
    }
}
