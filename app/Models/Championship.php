<?php

namespace App\Models;

use App\Collections\ChampionshipCollection;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Championship extends Pivot
{
    use Presentable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'championships';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'won_on' => 'datetime',
        'lost_on' => 'datetime',
    ];

    protected $fillable = ['won_on', 'lost_on', 'successful_defenses'];

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\ChampionshipPresenter';

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new ChampionshipCollection($models);
    }
}
