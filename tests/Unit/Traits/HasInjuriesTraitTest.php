<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasInjuriesTraitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_active_wrestler_can_be_injured()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->injure();

        $this->assertEquals(1, $wrestler->injuries->count());
        $this->assertFalse($wrestler->is_active);
        $this->assertNull($wrestler->injuries()->first()->healed_at);
        $this->assertTrue($wrestler->isInjured());
    }

    /** @test */
    public function an_injured_wrestler_can_recover_from_an_injury()
    {
        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $wrestler->recover();

        $this->assertNotNull($wrestler->injuries()->first()->healed_at);
        $this->assertTrue($wrestler->is_active);
        $this->assertFalse($wrestler->isInjured());
    }

    /** @test */
    public function a_wrestler_can_have_many_injuries()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $wrestler->injuries);
    }

    /** @test */
    public function it_can_get_injured_wrestlers()
    {
        $wrestlerA = factory(Wrestler::class)->states('injured')->create();
        $wrestlerB = factory(Wrestler::class)->states('injured')->create();
        $wrestlerC = factory(Wrestler::class)->states('active')->create();

        $injuredWrestlers = Wrestler::injured()->get();

        $this->assertTrue($injuredWrestlers->contains($wrestlerA));
        $this->assertTrue($injuredWrestlers->contains($wrestlerB));
        $this->assertFalse($injuredWrestlers->contains($wrestlerC));
    }

    /**
     * @expectedException \App\Exceptions\WrestlerAlreadyInjuredException
     *
     * @test
     */
    public function an_injured_wrestler_cannot_be_injured_without_being_healed_first()
    {
        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $wrestler->injure();
    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotInjuredException
     *
     * @test
     */
    public function a_active_wrestler_cannot_recover_from_an_injury_without_being_injured()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->recover();
    }
}
