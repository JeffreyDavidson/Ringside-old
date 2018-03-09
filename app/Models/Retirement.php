<?php

namespace App\Models;

use Carbon\Carbon;
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
     * Ends a retirement.
     *
     * @param string $date Datetime that represents the date and time the retirement ended.
     * @return bool
     */
    public function end()
    {
        return tap($this)->update([
            'ended_at' => Carbon::now()
        ]);
    }
}
