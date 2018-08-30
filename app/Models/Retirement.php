<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;

class Retirement extends Model
{
    use Presentable;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'retired_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\RetirementPresenter';

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
}
