<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Injury extends Model
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
    protected $dates = ['injured_at', 'healed_at'];

    /**
     * Heals an injury.
     *
     * @param string $date Datetime that represents the date and time the injury healed.
     * @return bool
     */
    public function heal()
    {
        return tap($this)->update([
            'healed_at' => Carbon::now()
        ]);
    }
}
