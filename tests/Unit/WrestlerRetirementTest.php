<?php

namespace Tests\Unit;

use App\Wrestler;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class WrestlerRetirementTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_wrestler_can_retire()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->retire();

        $this->assertCount(1, $wrestler->retirements);
        $this->assertNull($wrestler->retirements->first()->ended_at);
    }

    /** @test */
    public function a_wrestler_can_unretire()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->retire();
        $wrestler->unretire();

        $this->assertCount(1, $wrestler->retirements);
        $this->assertNotNull($wrestler->retirements->first()->ended_at);
    }
}
