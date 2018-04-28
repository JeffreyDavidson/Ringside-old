<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Wrestler;
use App\Models\WrestlerStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WrestlerTest extends TestCase
{
    use RefreshDatabase;

    protected $wrestler;

    public function setUp()
    {
        parent::setUp();

        $this->wrestler = factory(Wrestler::class)->create();
    }

    /** @test */
    public function a_wrestler_has_a_status()
    {
        $this->assertInstanceOf(WrestlerStatus::class, $this->wrestler->status);
    }

    /** @test */
    public function a_wrestler_can_have_many_managers()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->wrestler->managers);
    }

    /** @test */
    public function a_wrestler_can_hold_many_titles()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->wrestler->titles);
    }

    /** @test */
    public function a_wrestler_can_have_many_matches()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->wrestler->matches);
    }

    /** @test */
    public function a_wrestler_can_have_many_injuries()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->wrestler->injuries);
    }

    /** @test */
    public function a_wrestler_can_have_many_suspensions()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->wrestler->suspensions);
    }

    /** @test */
    public function a_wrestler_can_have_many_retirements()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->wrestler->retirements);
    }
}
