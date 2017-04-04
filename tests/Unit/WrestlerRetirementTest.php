<?php

namespace Tests\Unit;

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

        $this->assertEquals('6\'1"', $bio->formatted_height);
    }

    /** @test */
    public function a_wrestler_can_unretire()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->unretire();

        $this->assertEquals('6\'1"', $bio->formatted_height);
    }
}
