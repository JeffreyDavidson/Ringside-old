<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retirement extends Model
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
    protected $dates = ['retired_at', 'ended_at'];

    /**
     * TODO: Find out what I should do about type for date.
     * REVIEW: Decide if this should be kept in this class or moved to a service class.
     * Lifts a wrestler's suspension.
     *
     * @param string $date Datetime that represents the date and time the wrestler was renewed.
     * @return boolean
     */
    public function lift($date = null)
    {
        return $this->update(['ended_at' => $date ?: $this->freshTimestamp()]);
    }
}
