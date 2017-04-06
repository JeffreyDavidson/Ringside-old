<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Title extends Model
{
    public function wrestler()
    {
        return $this->hasMany(TitleHistory::class);
    }

    public function matches()
    {
        return $this->belongsToMany(Match::class);
    }

    public function scopeIntroducedBefore($query, $date)
    {
        return $query->where('introduced_at', '<=', $date);
    }
}
