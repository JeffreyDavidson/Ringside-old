<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function healed()
    {
        return $this->update(['healed_at' => Carbon::now()]);
    }

    public function injured()
    {
        return $this;
    }
}
