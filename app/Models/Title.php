<?php

namespace App\Models;

use App\Traits\HasMatches;
use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasRetirements;

class Title extends Model
{
    use HasMatches, HasRetirements, Presentable, SoftDeletes;

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\TitlePresenter';

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
    protected $dates = ['introduced_at', 'retired_at'];

    /**
     * A title can have many champions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function champions()
    {
        return $this->hasMany(Champion::class);
    }

    /**
     * A title can be added to many matches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function matches()
    {
        return $this->belongsToMany(Match::class);
    }

    /**
     * A title can have many retirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function retirements()
    {
        return $this->morphMany(Retirement::class, 'retireable');
    }

    /**
     * Crowns the new champion for the title.
     *
     * @param \App\Models\Wrestler $wrestler
     * @param datetime $date
     * @return void
     */
    public function setChampion(Wrestler $wrestler, $date)
    {
        $champion = $this->currentChampion;

        if ($champion !== null && $wrestler->id !== $champion->id) {
            $champion->wrestler->loseTitle($this, $date);
        }

        if ($champion === null || $wrestler->id !== $champion->id) {
            $wrestler->winTitle($this, $date);
        }

        $this->setRelation('currentChampion', $wrestler);
    }

    /**
     * Returns the current champion for the title.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return App\Models\Wrestler
     */
    public function currentChampion()
    {
        return $this->champions()->whereNull('lost_on')->toHasOne();
    }

    public function hasAChampion()
    {
        return $this->champions()->whereNull('lost_on')->exists();
    }

    /**
     * Scope a query to only include active titles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query, $date)
    {
        return $query->where('introduced_at', '<=', $date)->whereNull('retired_at');
    }

    /**
     * Scope a query to only retired titles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRetired($query)
    {
        return $query->whereNotNull('retired_at');
    }
}
