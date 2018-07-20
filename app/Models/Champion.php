<?php

namespace App\Models;

use Carbon\Carbon;
use App\Collections\ChampionCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Laracodes\Presenter\Traits\Presentable;

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
