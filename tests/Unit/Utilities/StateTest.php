<?php

namespace Tests\Unit\Utilities;

use App\Http\Utilities\State;
use Tests\IntegrationTestCase;

class StateTest extends IntegrationTestCase
{
    /** @test */
    public function it_can_get_all_states()
    {
        $this->assertEquals(51, count(State::all()));
    }
}
