<?php

namespace Tests\Unit;

use App\Models\Wrestler;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HasRetirementsTraitTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_wrestler_can_retire()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->retire();

        $this->assertEquals(1, $wrestler->retirements->count());
        $this->assertEquals(2, $wrestler->status());
        $this->assertNull($wrestler->retirements()->first()->ended_at);
    }

    /** @test */
    public function a_wrestler_can_unretire()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->retire();
        $wrestler->unretire();

        $this->assertNotNull($wrestler->retirements()->first()->ended_at);
        $this->assertEquals(1, $wrestler->status());
    }

    /** @test */
    public function a_wrestler_can_have_multiple_retirements()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->retire();
        $wrestler->unretire();
        $wrestler->retire();

        $this->assertTrue($wrestler->hasPreviousRetirements());
        $this->assertEquals(1, $wrestler->previousRetirements->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerAlreadyRetiredException
     *
     * @test
     */
    public function a_wrestler_who_is_retired_cannot_retire()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->retire();
        $wrestler->retire();

        $this->assertEquals(1, $wrestler->retirements->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotRetiredException
     *
     * @test
     */
    public function a_wrestler_who_is_not_retired_cannot_unretire()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->unretire();

        $this->assertEquals(0, $wrestler->retirements->count());
    }
}
