<?php

namespace App\Models;

use App\Presenters\EventPresenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Laracodes\Presenter\Traits\Presentable;
use App\Exceptions\EventIsScheduledException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Exceptions\EventAlreadyArchivedException;
use App\Exceptions\EventNotAlreadyArchivedException;

class Event extends Model
{
    use Presentable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'date', 'venue_id', 'number_of_matches', 'archived_at'];

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
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = EventPresenter::class;

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
     * @return bool
     *
     * @throws \App\Exceptions\EventAlreadyArchivedException
     * @throws \App\Exceptions\EventIsScheduledException
     */
    public function archive()
    {
        if ($this->isArchived()) {
            throw new EventAlreadyArchivedException;
        }

        if ($this->isScheduled()) {
            throw new EventIsScheduledException;
        }

        return $this->update(['archived_at' => now()]);
    }

    /**
     * Unarchive an event.
     *
     * @return bool
     *
     * @throws \App\Exceptions\EventNotAlreadyArchivedException
     * @throws \App\Exceptions\EventIsScheduledException
     */
    public function unarchive()
    {
        if (! $this->isArchived()) {
            throw new EventNotAlreadyArchivedException;
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
        return $query->where('date', '<', today())->whereNull('archived_at');
    }

    /**
     * Scope a query to only include archived events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Query\Builder
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
        return ! is_null($this->archived_at);
    }
}
