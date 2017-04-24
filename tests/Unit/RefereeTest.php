<?php

namespace Tests\Unit;

use App\Models\Referee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RefereeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_arena_can_hold_many_event()
    {
        $referee = factory(Referee::class)->make(['first_name' => 'Jeffrey', 'last_name' => 'Davidson']);

        $this->assertEquals('Jeffrey Davidson', $referee->full_name);
    }
}
