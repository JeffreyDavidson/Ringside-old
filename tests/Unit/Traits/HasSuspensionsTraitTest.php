<?php

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
public function a_wrestler_can_be_renewed()
{
    $wrestler = factory(Wrestler::class)->create();

    $wrestler->suspend();
    $wrestler->renew();

    $this->assertNotNull($wrestler->suspensions()->first()->ended_at);
    $this->assertEquals(1, $wrestler->status());
}

/** @test */
public function a_wrestler_can_have_multiple_suspensions()
{
    $wrestler = factory(Wrestler::class)->create();

    $wrestler->suspend();
    $wrestler->renew();
    $wrestler->suspend();

    $this->assertTrue($wrestler->hasPastSuspensions());
    $this->assertEquals(1, $wrestler->pastSuspensions->count());
}

/**
 * @expectedException \App\Exceptions\WrestlerAlreadySuspendedException
 *
 * @test
 */
public function a_wrestler_who_is_suspended_cannot_be_suspended()
{
    $wrestler = factory(Wrestler::class)->create();
    $wrestler->suspend();

    $wrestler->suspend();

    $this->assertEquals(1, $wrestler->suspended->count());
}

/**
 * @expectedException \App\Exceptions\WrestlerNotSuspendedException
 *
 * @test
 */
public function a_wrestler_who_is_not_suspended_cannot_be_renewed()
{
    $wrestler = factory(Wrestler::class)->create();

    $wrestler->renew();

    $this->assertEquals(0, $wrestler->retirements->count());
}