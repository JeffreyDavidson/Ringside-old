<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;

class Retirement extends Model
{
    use Presentable;

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\RetirementPresenter';

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
     * @param  string|null  $date
     * @return bool
     */
    public function end($date = null)
    {
        return tap($this)->update(['ended_at' => $date ?: $this->freshTimestamp()]);
    }
}
