<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use App\Models\Roster\Wrestler;
use App\Models\Suspension;
use Tests\IntegrationTestCase;

class SuspensionTest extends IntegrationTestCase
{
    /** @test */
    public function a_wrestler_can_be_suspended()
    {
        $wrestler = factory(Wrestler::class)->create();
        $suspension = factory(Suspension::class)->create(['suspendable_id' => $wrestler->id, 'suspendable_type' => get_class($wrestler)]);
        
        $this->assertTrue($suspension->suspendee->is($wrestler));
    }

    /** @test */
    public function a_suspension_has_a_started_at_date()
    {
        $suspension = factory(Suspension::class)->create(['suspended_at' => '2018-10-31']);

        $this->assertEquals('2018-10-31', $suspension->suspended_at->toDateString());
    }

    /** @test */
    public function a_suspension_has_an_ended_at_date()
    {
        $suspension = factory(Suspension::class)->create(['ended_at' => '2018-10-31']);

        $this->assertEquals('2018-10-31', $suspension->ended_at->toDateString());
    }

    /** @test */
    public function a_suspension_can_be_lifted()
    {
        $suspension = factory(Suspension::class)->create();

        $suspension->lift();

        $this->assertEquals(Carbon::now()->toDateString(), $suspension->ended_at->toDateString());
    }

    /** @test */
    public function a_suspension_can_be_listed_with_a_specific_ended_at_date()
    {
        $suspension = factory(Suspension::class)->create();

        $suspension->lift(Carbon::parse('2018-10-31'));

        $this->assertEquals('2018-10-31', $suspension->ended_at->toDateString());
    }
}
