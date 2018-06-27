<?php

namespace App\Models;

use App\Traits\HasTitles;
use App\Traits\HasMatches;
use App\Traits\HasInjuries;
use App\Traits\HasManagers;
use App\Traits\HasRetirements;
use App\Traits\HasSuspensions;
use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wrestler extends Model
{
    use HasManagers, HasTitles, HasRetirements, HasSuspensions,
        HasInjuries, HasMatches, SoftDeletes, Presentable;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_INJURED = 'injured';
    public const STATUS_SUSPENDED = 'suspended';
    public const STATUS_RETIRED = 'retired';

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
    public function championships()
    {
        return $this->hasMany(Champion::class);
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

    public function getAvailableStatusesAttribute()
    {
        // These are the default options; if $current is equal to null, this is all
        // that will be returned.
        $options = collect([Wrestler::STATUS_ACTIVE, Wrestler::STATUS_INACTIVE]);

        // This part should be self-explanatory, but if you have any questions, just ask.
        switch ($this->status) {
            case Wrestler::STATUS_ACTIVE:
                return $options->merge([Wrestler::STATUS_INJURED, Wrestler::STATUS_SUSPENDED, Wrestler::STATUS_RETIRED]);

            case Wrestler::STATUS_INJURED:
                return $options->merge([Wrestler::STATUS_INJURED, Wrestler::STATUS_RETIRED]);

            case Wrestler::STATUS_SUSPENDED:
                return $options->merge([Wrestler::STATUS_SUSPENDED, Wrestler::STATUS_RETIRED]);

            case Wrestler::STATUS_RETIRED:
                return $options->merge([Wrestler::STATUS_RETIRED]);
        }

        return $options->values();
    }

    public function is($status) {
        return $this->status === $status;
    }

    public function scopeActive(Builder $query) {
        $query->where('status', Wrestler::STATUS_ACTIVE);
    }

    public function scopeInactive(Builder $query) {
        $query->where('status', Wrestler::STATUS_INACTIVE);
    }

    public function scopeInjured(Builder $query) {
        $query->where('status', Wrestler::STATUS_INJURED);
    }

    public function scopeSuspended(Builder $query) {
        $query->where('status', Wrestler::STATUS_SUSPENDED);
    }

    public function scopeRetired(Builder $query) {
        $query->where('status', Wrestler::STATUS_RETIRED);
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
