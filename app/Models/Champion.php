<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use App\Collections\TitleChampionsCollection;

class Champion extends Model
{
    use Presentable;

    protected $presenter = 'App\Presenters\TitleHistoryPresenter';

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
        return $this->belongsTo(Wrestler::class);
    }

    public function loseTitle()
    {
        return $this->update(['lost_on' => Carbon::now()]);
    }

    public static function getCurrentChampion()
    {
        return $this->wrestler->whereNotNull('lost_on');
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new TitleChampionsCollection($models);
    }
}
