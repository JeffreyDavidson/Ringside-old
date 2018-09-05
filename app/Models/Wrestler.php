<?php

namespace App\Models;

use App\Traits\HasStatus;
use App\Traits\HasTitles;
use App\Traits\HasMatches;
use App\Traits\HasInjuries;
use App\Traits\HasManagers;
use App\Traits\HasSuspensions;
use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use App\Traits\HasRetirements;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Hireable;

class Wrestler extends Model
{
    use HasInjuries,
    HasManagers,
    HasMatches,
    HasStatus,
    HasSuspensions,
    HasTitles,
    HasRetirements,
    Hireable,
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
