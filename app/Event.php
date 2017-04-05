<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function matches()
    {
        return $this->hasMany(Match::class);
    }

    public function addMatches($matches)
    {
//        dd($matches);
        $this->matches()->createMany($matches);
    }
}
