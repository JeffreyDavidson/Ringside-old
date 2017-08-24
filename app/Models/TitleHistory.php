<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use App\Collections\TitleHistories;

class TitleHistory extends Model
{
    use Presentable;

    protected $presenter = 'App\Presenters\TitleHistoryPresenter';

    protected $table = 'title_wrestler';

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

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array $models
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new TitleHistories($models);
    }

    public function loseTitle($date = null)
    {
        return $this->update(['lost_on' => $date]);
    }
}
