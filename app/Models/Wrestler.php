<?php

namespace App\Models;

use App\Traits\Hireable;
use App\Traits\HasStatus;
use App\Traits\HasTitles;
use App\Traits\HasMatches;
use App\Traits\HasInjuries;
use App\Traits\HasManagers;
use App\Traits\HasSuspensions;
use App\Traits\HasRetirements;
use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wrestler extends Model
{
    use Hireable,
    HasStatus,
    HasTitles,
    HasMatches,
    HasInjuries,
    HasManagers,
    HasSuspensions,
    HasRetirements,
    Presentable,
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
    protected $fillable = ['name', 'slug', 'hometown', 'height', 'weight', 'signature_move', 'is_active', 'hired_at'];

    /**
     * Assign which presenter to be used for model.
     *
     * @var string
     */
    protected $presenter = 'App\Presenters\WrestlerPresenter';
}
