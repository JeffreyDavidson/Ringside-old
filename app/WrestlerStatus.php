<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WrestlerStatus extends Model
{
    const ACTIVE = 1, INACTIVE = 2, INJURED = 3, SUSPENDED = 4, RETIRED = 5;
}
