<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Suspension;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuspensionTest extends TestCase
{
    use RefreshDatabase;

    protected $suspension;

    public function setUp()
    {
        parent::setUp();

        $this->suspension = factory(Suspension::class)->create();
    }

    /** @test */
    public function a_suspension_can_be_lifted()
    {
        $this->suspension->lift();

        $this->assertEquals(Carbon::now()->toDateString(), $this->suspension->ended_at->toDateString());
    }
}
