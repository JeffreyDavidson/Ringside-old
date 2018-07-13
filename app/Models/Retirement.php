<?php

namespace App\Models;

use Carbon\Carbon;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\Model;

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
     * @param string $date Datetime that represents the date and time the retirement ended.
     * @return bool
     */
    public function end()
    {
        return tap($this)->update([
            'ended_at' => Carbon::now(),
        ]);
    }
}
