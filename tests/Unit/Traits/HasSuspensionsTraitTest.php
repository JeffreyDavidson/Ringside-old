<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Wrestler;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasSuspensionsTraitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_wrestler_can_be_suspended()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->suspend();

        $this->assertEquals(1, $wrestler->suspensions->count());
        $this->assertFalse($wrestler->is_active);
        $this->assertNull($wrestler->suspensions()->first()->ended_at);
        $this->assertTrue($wrestler->isSuspended());
    }

    /** @test */
    public function a_supended_wrestler_can_be_unsuspened()
    {
        $wrestler = factory(Wrestler::class)->create()->suspend();

        $wrestler->unsuspend();

        $this->assertNotNull($wrestler->suspensions->last()->ended_at);
        $this->assertTrue($wrestler->is_active);
        $this->assertFalse($wrestler->isSuspended());
    }

    /** @test */
    public function a_wrestler_can_have_multiple_suspensions()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->suspend();
        $wrestler->unsuspend();
        $wrestler->suspend();

        $this->assertTrue($wrestler->hasPastSuspensions());
        $this->assertEquals(1, $wrestler->pastSuspensions->count());
    }

    /** @test */
    public function it_can_get_suspended_wrestlers()
    {
        $wrestlerA = factory(Wrestler::class)->states('suspended')->create();
        $wrestlerB = factory(Wrestler::class)->states('suspended')->create();
        $wrestlerC = factory(Wrestler::class)->states('active')->create();

        $suspendedWrestlers = Wrestler::suspended()->get();

        $this->assertTrue($suspendedWrestlers->contains($wrestlerA));
        $this->assertTrue($suspendedWrestlers->contains($wrestlerB));
        $this->assertFalse($suspendedWrestlers->contains($wrestlerC));
    }

    /**
     * @expectedException \App\Exceptions\WrestlerAlreadySuspendedException
     *
     * @test
     */
    public function a_suspended_wrestler_cannot_be_suspended()
    {
        $wrestler = factory(Wrestler::class)->create()->suspend();

        $wrestler->suspend();

        $this->assertEquals(1, $wrestler->suspensions->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotSuspendedException
     *
     * @test
     */
    public function a_wrestler_who_is_not_suspended_cannot_be_unsuspended()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->unsuspend();

        $this->assertEquals(0, $wrestler->suspensions->count());
    }
}