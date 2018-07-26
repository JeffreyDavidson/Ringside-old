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
     * Lifts a suspension.
     *
     * @param string $date Datetime that represents the date and time the suspension was lifted.
     * @return bool
     */
    public function lift()
    {
        return tap($this)->update([
            'ended_at' => Carbon::now(),
        ]);
    }
}
