<?php

namespace Tests\Unit;

use App\Exceptions\WrestlerNotInjuredException;
use App\Exceptions\WrestlerCannotBeInjuredException;
use App\Models\Wrestler;
use App\Models\Manager;
use App\Models\Title;
use App\Models\WrestlerStatus;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class WrestlerTest extends TestCase
{
    use DatabaseMigrations;

}
