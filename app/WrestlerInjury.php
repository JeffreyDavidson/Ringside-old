<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WrestlerInjury extends Model
{
    protected $guarded = [];

    protected $dates = ['injured_at', 'healed_at'];

    public function healed($date)
    {
        return $this->update(['healed_at' => $date]);
    }

    public function injured()
    {
        return $this;
    }
}
