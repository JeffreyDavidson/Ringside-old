<?php

namespace Tests\Feature\Unit;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasInjuriesTraitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_wrestler_can_be_injured()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->injure();

        $this->assertEquals(1, $wrestler->injuries->count());
        $this->assertEquals(2, $wrestler->status());
        $this->assertNull($wrestler->injuries()->first()->healed_at);
        $this->assertTrue($wrestler->isInjured());
    }

    /** @test */
    public function an_injured_wrestler_can_be_recovered()
    {
        $wrestler = factory(Wrestler::class)->create()->injure();

        $wrestler->recover();

        $this->assertNotNull($wrestler->injuries()->first()->healed_at);
        $this->assertEquals(1, $wrestler->status());
        $this->assertFalse($wrestler->isInjured());
    }

    /** @test */
    public function a_wrestler_can_have_multiple_injuries()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->injure();
        $wrestler->recover();
        $wrestler->injure();

        $this->assertTrue($wrestler->hasPastInjuries());
        $this->assertEquals(1, $wrestler->pastInjuries->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerAlreadyInjuredException
     *
     * @test
     */
    public function an_injured_wrestler_cannot_be_injured()
    {
        $wrestler = factory(Wrestler::class)->create()->injure();

        $wrestler->injure();

        $this->assertEquals(1, $wrestler->injuries->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotInjuredException
     *
     * @test
     */
    public function a_wrestler_who_is_not_injured_cannot_be_recovered()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->recover();

        $this->assertEquals(0, $wrestler->injuries->count());
    }
}
