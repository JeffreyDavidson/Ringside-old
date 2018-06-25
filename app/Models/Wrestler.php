<?php

namespace App\Models;

use App\Traits\HasTitles;
use App\Traits\HasMatches;
use App\Traits\HasInjuries;
use App\Traits\HasManagers;
use App\Traits\HasStatuses;
use App\Traits\HasRetirements;
use App\Traits\HasSuspensions;
use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wrestler extends Model
{
    use HasStatuses, HasManagers, HasTitles, HasRetirements, HasSuspensions,
        HasInjuries, HasMatches, SoftDeletes, Presentable;

    public const STATUS_ACTIVE = 'Active';
    public const STATUS_INACTIVE = 'Inactive';
    public const STATUS_INJURED = 'Injured';
    public const STATUS_SUSPENDED = 'Suspended';
    public const STATUS_RETIRED = 'Retired';

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\WrestlerPresenter';

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
    protected $dates = ['hired_at'];

    /**
     * A wrestler can have many managers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function managers()
    {
        return $this->belongsToMany(Manager::class);
    }

    /**
     * A wrestler can hold many titles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function titles()
    {
        return $this->hasManyThrough(Title::class, Champion::class);
    }

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
     * A wrestler can have many injuries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function injuries()
    {
        return $this->hasMany(Injury::class);
    }

    /**
     * A wrestler can have many suspensions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function suspensions()
    {
        return $this->hasMany(Suspension::class);
    }

    /**
     * Scope a query to only include wrestlers hired before a specific date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHiredBefore($query, $date)
    {
        return $query->where('hired_at', '<', $date);
    }

    /**
     * Scope a query to only include wrestlers hired before a specific date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function scopeHiredBefore($query, $date)
    // {
    //     return $query->whereDoesntHave('retirements', function ($q) {
    //         $q->whereNotNull('ended_at');
    //     });
    // }

}
