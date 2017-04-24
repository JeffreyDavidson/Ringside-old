<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Collections\TitleHistories;

class TitleHistory extends Model
{

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
        return $this->belongsTo(Title::class);
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

    public function getFormattedWonOnAttribute()
    {
        return $this->won_on->format('F j, Y');
    }

    public function getFormattedLostOnAttribute()
    {
        return $this->lost_on ? $this->lost_on->format('F j, Y') : 'Present';
    }
}
