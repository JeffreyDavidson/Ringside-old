<?php

namespace App\Models;

use App\Traits\HasRetirements;
use App\Traits\HasStatus;
use App\Traits\HasSuspensions;
use App\Traits\Hireable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracodes\Presenter\Traits\Presentable;

class Referee extends Model
{
    use Hireable,
    HasStatus,
    Presentable,
    HasRetirements,
    HasSuspensions,
        SoftDeletes;

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
    protected $presenter = 'App\Presenters\RefereePresenter';

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
