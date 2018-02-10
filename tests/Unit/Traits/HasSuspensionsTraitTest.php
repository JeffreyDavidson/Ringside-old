<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HasSuspensionsTraitTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_wrestler_can_be_suspended()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->suspend();

        $this->assertEquals(1, $wrestler->suspensions->count());
        $this->assertEquals(2, $wrestler->status());
        $this->assertNull($wrestler->suspensions()->first()->ended_at);
    }

    /** @test */
    public function a_wrestlers_suspension_can_be_lifted()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->suspend();
        $wrestler->lift();

        $this->assertNotNull($wrestler->suspensions()->first()->ended_at);
        $this->assertEquals(1, $wrestler->status());
    }

    /** @test */
    public function a_wrestler_can_have_multiple_suspensions()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->suspend();
        $wrestler->suspensions->lift();
        $wrestler->suspend();

        $this->assertTrue($wrestler->hasPastSuspensions());
        $this->assertEquals(1, $wrestler->pastSuspensions->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerAlreadySuspendedException
     *
     * @test
     */
    public function a_wrestler_who_is_suspended_cannot_be_suspended_again_until_lifted()
    {
        $wrestler = factory(Wrestler::class)->create();
        $wrestler->suspend();

        $wrestler->suspend();

        $this->assertEquals(1, $wrestler->suspensions->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotSuspendedException
     *
     * @test
     */
    public function a_wrestler_who_is_not_suspended_cannot_have_their_suspension_lifted()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->lift();

        $this->assertEquals(0, $wrestler->suspensions->count());
    }
}