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
     * Unretires a wrestler who is currently retired.
     *
     * @return boolean
     */
    public function unretire()
    {
        return $this->update(['ended_at' => $this->freshTimestamp()]);
    }
}
