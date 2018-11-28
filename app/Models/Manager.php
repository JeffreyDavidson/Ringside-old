<?php

namespace App\Models;

use App\Traits\Hireable;
use App\Traits\HasStatus;
use App\Traits\HasRetirements;
use App\Traits\HasSuspensions;
use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manager extends Model
{
    use Hireable, HasStatus, HasRetirements, HasSuspensions, Presentable, SoftDeletes;

    protected $casts = [
        'is_active' => 'boolean',
        'hired_at' => 'datetime',
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
    protected $presenter = 'App\Presenters\ManagerPresenter';
}
