<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Retirement;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RetirementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_retirement_can_be_ended()
    {
        $retirement = factory(Retirement::class)->create();

        $retirement->end();

        $this->assertEquals(Carbon::now()->toDateString(), $this->retirement->ended_at->toDateString());
    }
}
