<?php

namespace Tests\Unit;

use App\Models\Wrestler;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HasInjuriesTraitTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_wrestler_can_be_injured()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->injure();

        $this->assertEquals(1, $wrestler->injuries->count());
        $this->assertEquals(2, $wrestler->status());
        $this->assertNull($wrestler->injuries()->first()->healed_at);
    }

    /** @test */
    public function an_injured_wrestler_can_be_healed()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->injure();
        $wrestler->heal();

        $this->assertNotNull($wrestler->injuries()->first()->healed_at);
        $this->assertEquals(1, $wrestler->status());
    }

    /** @test */
    public function a_wrestler_can_have_multiple_injuries()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->injure();
        $wrestler->heal();
        $wrestler->injure();

        $this->assertTrue($wrestler->hasPreviousInjuries());
        $this->assertEquals(1, $wrestler->previousInjuries->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerAlreadyInjuredException
     *
     * @test */
    public function an_injured_wrestler_cannot_be_injured()
    {
        $wrestler = factory(Wrestler::class)->create();
        $wrestler->injure();

        $wrestler->injure();

        $this->assertEquals(1, $wrestler->injuries->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotInjuredException
     *
     * @test
     */
    public function a_wrestler_who_is_not_injured_cannot_be_healed()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->heal();

        $this->assertEquals(0, $wrestler->injuries->count());
    }


}
