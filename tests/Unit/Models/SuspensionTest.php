<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Suspension;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SuspensionTest extends TestCase
{
    use DatabaseMigrations;

    protected $suspension;

    public function setUp()
    {
        parent::setUp();

        $this->suspension = factory(Suspension::class)->create();
    }

    /** @test */
    public function a_suspension_is_lifted_on_the_current_day()
    {
        $this->suspension->lift();

        $this->assertEquals(Carbon::today()->toDateString(), $this->suspension->ended_at->toDateString());
    }
}
