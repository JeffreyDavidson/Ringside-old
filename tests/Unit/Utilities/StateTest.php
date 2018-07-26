<?php

namespace Tests\Unit\Utilities;

use Tests\TestCase;
use App\Http\Utilities\State;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_all_states()
    {
        $this->assertEquals(51, count(State::all()));
    }
}
