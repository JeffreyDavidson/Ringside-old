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
     * Lifts a suspension.
     *
     * @param  string|null  $date
     * @return bool
     */
    public function lift($date = null)
    {
        return tap($this)->update(['ended_at' => $date ?: $this->freshTimestamp()]);
    }
}
