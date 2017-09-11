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

    /** @test */
    public function can_have_matches()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $wrestler->matches);
    }
}
