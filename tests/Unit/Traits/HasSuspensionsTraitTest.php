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
    public function a_suspended_wrestler_can_be_unsuspened()
    {
        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $wrestler->unsuspend();

        $this->assertNotNull($wrestler->suspensions->last()->ended_at);
        $this->assertTrue($wrestler->is_active);
        $this->assertFalse($wrestler->isSuspended());
        $this->assertTrue($wrestler->hasPastSuspensions());
        $this->assertEquals(1, $wrestler->pastSuspensions->count());
    }

    /** @test */
    public function a_wrestler_can_have_many_suspensions()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $wrestler->suspensions);
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
        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $wrestler->suspend();
    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotSuspendedException
     *
     * @test
     */
    public function an_active_wrestler_cannot_be_unsuspended()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->unsuspend();
    }
}