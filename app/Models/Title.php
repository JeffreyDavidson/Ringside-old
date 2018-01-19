<?php

namespace App\Models;

use App\Queries\LongestTitleReignQuery;
use App\Queries\MostTitleDefensesQuery;
use App\Queries\MostTitleReignsQuery;
use App\Traits\HasMatches;
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
     * @param $wrestler
     * @param $date
     */
    public function setNewChampion($wrestler, $date)
    {
        if (!is_null($this->getCurrentChampion())) {
            $this->getCurrentChampion()->loseTitle($this, $date);
        }

        $wrestler->winTitle($this, $date);
    }

    /**
     * Get the wrestler who has defended the title the most times.
     *
     * @return static
     */
    public function most_title_defenses()
    {
        return MostTitleDefensesQuery::get($this);
    }

    /**
     * Get the wrestler who has held the title the most times.
     *
     * @return Wrestler $wrestler
     */
    public function most_title_reigns()
    {
        return MostTitleReignsQuery::get($this);
    }

    /**
     * Get the current champion for the title.
     *
     * @return Wrestler $wrestler|null
     */
    public function getCurrentChampion()
    {
        return $this->champions()->whereNull('lost_on')->first() ? $this->champions()->whereNull('lost_on')->first()->wrestler : null;
    }

    public function longest_title_reign()
    {
        return LongestTitleReignQuery::get($this);
    }
}
