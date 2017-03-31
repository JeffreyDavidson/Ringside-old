<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WrestlerRetire extends Model
{
    protected $guarded = [];

    protected $table = 'wrestler_retirements';

    public function unretire()
    {
        return $this->update(['ended_at' => Carbon::now()]);
    }
}
