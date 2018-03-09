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
        $this->assertEquals(2, $wrestler->status());
        $this->assertNull($wrestler->suspensions()->first()->ended_at);
        $this->assertTrue($wrestler->isSuspended());
    }

    /** @test */
    public function a_supended_wrestler_can_be_unsuspened()
    {
        $wrestler = factory(Wrestler::class)->create()->suspend();

        $wrestler->unsuspend();

        $this->assertNotNull($wrestler->suspensions->last()->ended_at);
        $this->assertEquals(1, $wrestler->status());
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