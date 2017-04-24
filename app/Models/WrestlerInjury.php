<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WrestlerInjury extends Model
{
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
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
