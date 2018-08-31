<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Collections\ChampionshipCollection;
use Laracodes\Presenter\Traits\Presentable;

class Championship extends Model
{
    use Presentable;

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\ChampionshipPresenter';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'won_on' => 'datetime',
        'lost_on' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['wrestler_id', 'title_id', 'won_on', 'lost_on'];

    /**
     * A championship belongs to a title.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function title()
    {
        return $this->belongsTo(Title::class)->withTrashed();
    }

    /**
     * A championship belongs to a wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wrestler()
    {
        return $this->belongsTo(Wrestler::class)->withTrashed();
    }

    /**
     * A championship can be lost.
     *
     * @param  string|null  $date
     * @return $this
     */
    public function loseTitle($date = null)
    {
        return $this->update(['lost_on' => $date ?: $this->freshTimestamp()]);
    }

    /**
     * Scope to only include champions have haven't lost the title.
     *
     * @param  string|null  $date
     * @return $this
     */
    public function scopeCurrent(Builder $query)
    {
        return $query->whereNull('lost_on');
    }

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
