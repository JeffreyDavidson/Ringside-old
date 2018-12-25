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

    /** @test */
    public function a_retirement_retired_at_field_can_be_formatted()
    {
        $retirement = factory(Retirement::class)->create(['retired_at' => '2017-09-17']);

        $this->assertEquals('September 17, 2017', $retirement->formatted_retired_at);
    }
}
