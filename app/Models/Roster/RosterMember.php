<?php

namespace App\Models\Roster;

use App\Traits\Hireable;
use App\Traits\HasStatus;
use App\Traits\HasTitles;
use App\Traits\HasMatches;
use App\Traits\HasInjuries;
use App\Traits\HasManagers;
use App\Traits\HasRetirements;
use App\Traits\HasSuspensions;
use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class RosterMember extends Model
{
    use Hireable, HasStatus, HasTitles, HasMatches, HasInjuries, HasManagers, HasSuspensions, HasRetirements, Presentable, SoftDeletes;

    protected $casts = [
        'is_active' => 'boolean',
        'hired_at' => 'datetime',
    ];
}
