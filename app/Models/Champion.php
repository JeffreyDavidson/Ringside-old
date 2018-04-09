<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use Carbon\Carbon;
use App\Collections\ChampionCollection;

class Champion extends Model
{
    use Presentable;

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\ChampionPresenter';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['won_on', 'lost_on'];

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * A champion holds a title.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function title()
    {
        return $this->belongsTo(Title::class)->withTrashed();
    }

    /**
     * A champion is a wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wrestler()
    {
        return $this->belongsTo(Wrestler::class)->withTrashed();
    }

    /**
     * Scope a query to only return current champions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrent($query)
    {
        return $query->whereNull('lost_on');
    }

    /**
     * Calculates the length of time during championship reign.
     *
     * @return integer
     */
    public function timeSpentAsChampion()
    {
        $lostOn = $this->lost_on ?? Carbon::now();

        return $lostOn->diffInDays($this->won_on);
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new ChampionCollection($models);
    }
}
