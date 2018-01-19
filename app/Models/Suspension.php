<?php

namespace App\Models;

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
     * @param string $date Datetime that represents the date and time the wrestler was renewed.
     * @return boolean
     */
    public function renew($date = null)
    {
        return $this->update(['ended_at' => $date ?: $this->freshTimestamp()]);
    }
}
