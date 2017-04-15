<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stipulation extends Model
{
    public function matches()
    {
        return $this->belongsToMany(Match::class);
    }
}
