<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     * @param  string|null  $date
     * @return bool
     */
    public function heal($date = null)
    {
        return $this->update(['healed_at' => $date ?: $this->freshTimestamp()]);
    }
}
