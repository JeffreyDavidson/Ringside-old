<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Injury extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['injured_at', 'healed_at'];

    /**
     * The attributes that should be mutated to dates..
     *
     * @var array
     */
    protected $dates = [
        'injured_at', 'healed_at',
    ];

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
