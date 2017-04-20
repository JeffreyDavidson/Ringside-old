<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stipulation extends Model
{
    protected $guarded = [];

    public function matches()
    {
        return $this->belongsToMany(Match::class);
    }
}
