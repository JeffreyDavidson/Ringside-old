<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arena extends Model
{
    protected $guarded = [];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
