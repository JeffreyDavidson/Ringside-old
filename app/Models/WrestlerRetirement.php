<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WrestlerRetirement extends Model
{
    protected $guarded = [];

    protected $dates = ['retired_at', 'ended_at'];

    protected $table = 'wrestler_retirements';

    public function unretire()
    {
        return $this->update(['ended_at' => Carbon::now()]);
    }
}
