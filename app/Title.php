<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Title extends Model
{
    public function history()
    {
        return $this->hasMany(TitleHistory::class)->orderBy('won_on', 'desc');
    }

    public function wrestler()
    {
        return $this->belongsTo(Wrestler::class);
    }
}
