<?php

namespace App\Models\Roster;

use App\Traits\Hireable;
use App\Traits\HasStatus;
use App\Traits\HasRetirements;
use App\Traits\HasSuspensions;
use Illuminate\Database\Eloquent\Model;
use App\Presenters\Roster\RefereePresenter;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referee extends Model
{
    use Hireable;
    use HasStatus;
    use Presentable;
    use HasRetirements;
    use HasSuspensions;
    use SoftDeletes;

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
    protected $presenter = RefereePresenter::class;

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
