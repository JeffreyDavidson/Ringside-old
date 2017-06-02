<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WrestlerSuspension extends Model
{
    protected $guarded = [];

    protected $table = 'wrestler_suspensions';

    public function rejoin($date)
    {
        return $this->update(['ended_at' => $date]);
    }
}
