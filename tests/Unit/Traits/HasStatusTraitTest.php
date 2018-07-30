<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Wrestler;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasStatusTraitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_active_wrestlers()
    {
        $wrestlerA = factory(Wrestler::class)->states('active')->create();
        $wrestlerB = factory(Wrestler::class)->states('active')->create();
        $wrestlerC = factory(Wrestler::class)->states('inactive')->create();

        $activeWrestlers = Wrestler::active()->get();

        $this->assertTrue($activeWrestlers->contains($wrestlerA));
        $this->assertTrue($activeWrestlers->contains($wrestlerB));
        $this->assertFalse($activeWrestlers->contains($wrestlerC));
    }

    /** @test */
    public function it_can_get_inactive_wrestlers()
    {
        $wrestlerA = factory(Wrestler::class)->states('inactive')->create();
        $wrestlerB = factory(Wrestler::class)->states('inactive')->create();
        $wrestlerC = factory(Wrestler::class)->states('active')->create();

        $inactiveWrestlers = Wrestler::inactive()->get();

        $this->assertTrue($inactiveWrestlers->contains($wrestlerA));
        $this->assertTrue($inactiveWrestlers->contains($wrestlerB));
        $this->assertFalse($inactiveWrestlers->contains($wrestlerC));
    }

    /** @test */
    public function it_can_activate_an_inactive_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('inactive')->create();

        $wrestler->activate();

        $this->assertTrue($wrestler->is_active);
    }

    /** @test */
    public function it_can_deactivate_an_active_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->deactivate();

        $this->assertFalse($wrestler->is_active);
    }
}