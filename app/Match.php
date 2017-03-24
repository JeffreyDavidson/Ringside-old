<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    public function competitors()
    {
        return $this->belongsToMany(Wrestler::class);
    }
}
