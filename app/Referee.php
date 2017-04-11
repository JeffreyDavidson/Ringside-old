<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referee extends Model
{
    /**
     * Get the referee's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return preg_replace('/\s+/', ' ',$this->first_name.' '.$this->last_name);
    }
}
