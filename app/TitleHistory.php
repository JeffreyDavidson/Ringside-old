<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Collections\TitleHistories;

class TitleHistory extends Model
{
    protected $table = 'title_wrestler';
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
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new TitleHistories($models);
    }
}
