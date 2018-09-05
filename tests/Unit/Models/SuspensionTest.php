<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Suspension;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuspensionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_suspension_can_be_lifted()
    {
        $suspension = factory(Suspension::class)->create();

        $suspension->lift();

        $this->assertEquals(Carbon::now()->toDateString(), $suspension->ended_at->toDateString());
    }
}
