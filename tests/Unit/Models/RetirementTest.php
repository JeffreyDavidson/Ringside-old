<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use App\Models\Retirement;
use Tests\IntegrationTestCase;

class RetirementTest extends IntegrationTestCase
{
    /** @test */
    public function a_retirement_can_be_ended()
    {
        $retirement = factory(Retirement::class)->create();

        $retirement->end();

        $this->assertEquals(Carbon::now()->toDateString(), $retirement->ended_at->toDateString());
    }
}
