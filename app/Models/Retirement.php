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
     * Unretires a retired wrestler.
     *
     * @param string $date Datetime that represents the date and time the wrestler was unretired.
     *
     * @return bool
     */
    public function unretire($date = null)
    {
        return $this->update(['ended_at' => $date ?: $this->freshTimestamp()]);
    }
}
