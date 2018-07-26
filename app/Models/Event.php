<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Laracodes\Presenter\Traits\Presentable;
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
        return $this->hasMany(Match::class)->with('type', 'referees', 'stipulation', 'wrestlers', 'titles');
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
        return $this->matches()->orderBy('match_number', 'DESC')->toHasOne();
    }

    /**
     * Adds a match to the event.
     *
     * @param \App\Models\Match $match
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
        $this->update(['archived_at' => Carbon::now()]);
    }

    /**
     * Scope a query to only include scheduled events.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeScheduled(Builder $query)
    {
        return $query->where('date', '>=', Carbon::today());
    }

    /**
     * Scope a query to only include past events.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePast(Builder $query)
    {
        return $query->where('date', '<', Carbon::today());
    }

    /**
     * Scope a query to only include archived events.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeArchived(Builder $query)
    {
        return $query->whereNotNull('archived_at');
    }
}
