<?php

namespace App\Models;

use App\Collections\TitleChampionsCollection;
use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;

class Champion extends Model
{
    use Presentable;

    protected $presenter = 'App\Presenters\ChampionPresenter';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['won_on', 'lost_on'];

    protected $guarded = ['id'];

    public function title()
    {
        return $this->belongsTo(Title::class)->withTrashed();
    }

    public function wrestler()
    {
        return $this->belongsTo(Wrestler::class)->withTrashed();
    }

    public function loseTitle($date)
    {
        return $this->update(['lost_on' => $date ?: $this->freshTimestamp()]);
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param array $models
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new TitleChampionsCollection($models);
    }
}
