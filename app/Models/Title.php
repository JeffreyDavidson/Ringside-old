<?php

namespace App\Models;

use App\Traits\HasMatches;
use App\Queries\MostTitleReignsQuery;
use App\Queries\LongestTitleReignQuery;
use App\Queries\MostTitleDefensesQuery;
use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Title extends Model
{
    use HasMatches, Presentable, SoftDeletes;

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
     * Retrieve valid titles to be competed for.
     *
     * @param Builder $query
     * @param string $date
     * @return mixed
     */
    public function scopeValid($query, $date)
    {
        return $query->where('introduced_at', '<=', $date)->where(function ($query) use ($date) {
            $query->whereNull('retired_at')->orWhere('retired_at', '>', $date);
        });
    }

    /**
     * Crown the new champion.
     *
     * @param \App\Models\Wrestler $wrestler
     * @param datetime $date
     * @return void
     */
    public function setNewChampion(Wrestler $wrestler, $date)
    {
        if ($champion = $this->getCurrentChampion()) {
            $champion->loseTitle($this, $date);
        }

        $wrestler->winTitle($this, $date);
    }

    /**
     * Get the current champion for the title.
     *
     * @return Wrestler|null
     */
    public function getCurrentChampion()
    {
        if ($champion = $this->champions()->whereNull('lost_on')->first()) {
            return $champion->wrestler;
        }
    }
}
