<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retirement extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'retired_at', 'ended_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['retiree_type', 'retiree_id', 'retired_at', 'ended_at'];


    /**
     * Ends a retirement.
     *
     * @param  string|null  $date
     * @return bool
     */
    public function end($date = null)
    {
        return $this->update(['ended_at' => $date ?: $this->freshTimestamp()]);
    }

    /**
     * Formats retired at date for model.
     *
     * @return string
     */
    public function getFormattedRetiredAtDate()
    {
        return $this->retired_at->format('F j, Y');
    }
}
