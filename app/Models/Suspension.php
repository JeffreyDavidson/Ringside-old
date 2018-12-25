<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suspension extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'suspended_at', 'ended_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['suspended_at', 'ended_at'];

    /**
     * Retrieves the model that was suspended.
     *
     * @return \Illuminate\Database\Relations\MorphTo
     */
    public function suspendee()
    {
        return $this->morphTo('suspendable');
    }

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
