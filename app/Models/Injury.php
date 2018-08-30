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
    protected $fillable = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'injured_at' => 'datetime',
        'healed_at' => 'datetime',
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
