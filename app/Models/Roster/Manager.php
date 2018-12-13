<?php

namespace App\Models\Roster;

use App\Traits\Hireable;
use App\Traits\Retirable;
use App\Traits\Statusable;
use App\Traits\Suspendable;
use Illuminate\Database\Eloquent\Model;
use App\Presenters\Roster\ManagerPresenter;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manager extends Model
{
    use Hireable, Statusable, Retirable, Suspendable, Presentable, SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'hired_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'is_active', 'hired_at'];

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = ManagerPresenter::class;
}
