<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Suspension extends Model
{
    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['suspended_at', 'ended_at'];

    /**
     * Renews the wrestler from being suspended.
     *
     * @return boolean
     */
    public function renew()
    {
        return $this->update(['ended_at' => Carbon::now()]);
    }
}
