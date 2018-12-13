<?php

namespace Tests\Unit\Traits;

class InjurableTest
{

    /** @test */
    public function an_active_wrestler_can_be_injured()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->injure();

        $this->assertEquals(1, $wrestler->injuries->count());
        $this->assertFalse($wrestler->isActive());
        $this->assertNull($wrestler->injuries()->first()->healed_at);
        $this->assertTrue($wrestler->isInjured());
    }

    /**
     * @expectedException \App\Exceptions\ModelIsActiveException
     *
     * @test
     */
    public function an_active_wrestler_cannot_recover_from_an_injury_without_being_injured()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->recover();
    }

    /** @test */
    public function an_injured_wrestler_can_recover_from_an_injury()
    {
        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $wrestler->recover();

        $this->assertEquals(1, $wrestler->pastInjuries->count());
        $this->assertTrue($wrestler->isActive());
        $this->assertFalse($wrestler->isInjured());
    }

    /**
     * @expectedException \App\Exceptions\ModelIsInjuredException
     *
     * @test
     */
    public function an_injured_wrestler_cannot_be_injured_without_being_healed_first()
    {
        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $wrestler->injure();
    }

    /** @test */
    public function it_can_get_injured_wrestlers()
    {
        $injuredWrestlerA = factory(Wrestler::class)->states('injured')->create();
        $injuredWrestlerB = factory(Wrestler::class)->states('injured')->create();
        $activeWrestler = factory(Wrestler::class)->states('active')->create();

        $injuredWrestlers = Wrestler::injured()->get();

        $this->assertTrue($injuredWrestlers->contains($injuredWrestlerA));
        $this->assertTrue($injuredWrestlers->contains($injuredWrestlerB));
        $this->assertFalse($injuredWrestlers->contains($activeWrestler));
    }
}