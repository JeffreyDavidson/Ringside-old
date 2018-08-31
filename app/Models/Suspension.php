<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suspension extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'suspended_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['suspended_at'];

    /**
     * Lifts a suspension.
     *
     * @param  string|null  $date
     * @return bool
     */
    public function lift($date = null)
    {
        return $this->update(['ended_at' => $date ?: $this->freshTimestamp()]);
    }
}
