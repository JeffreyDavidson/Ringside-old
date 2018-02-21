<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Retirement;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RetirementTest extends TestCase
{
    use DatabaseMigrations;

    protected $retirement;

    public function setUp()
    {
        parent::setUp();

        $this->retirement = factory(Retirement::class)->create();
    }

    /** @test */
    public function a_retirement_can_be_ended()
    {
        $this->retirement->end();

        $this->assertEquals(Carbon::now()->toDateString(), $this->retirement->ended_at->toDateString());
    }
}
