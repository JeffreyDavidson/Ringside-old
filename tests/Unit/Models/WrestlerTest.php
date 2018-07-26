<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Wrestler;
use Carbon\Carbon;
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
    public function a_wrestler_can_have_many_managers()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->wrestler->managers);
    }

    /** @test */
    public function a_wrestler_can_hold_many_titles()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->wrestler->championships);
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

    /** @test */
    public function it_can_get_wrestlers_hired_before_a_certain_date()
    {
        $wrestlerA = factory(Wrestler::class)->create(['hired_at' => Carbon::parse('2016-12-31')]);
        $wrestlerB = factory(Wrestler::class)->create(['hired_at' => Carbon::parse('2014-12-31')]);
        $wrestlerC = factory(Wrestler::class)->create(['hired_at' => Carbon::parse('2017-01-01')]);

        $hiredWrestlers = Wrestler::hiredBefore(Carbon::parse('2017-01-01'))->get();

        $this->assertTrue($hiredWrestlers->contains($wrestlerA));
        $this->assertTrue($hiredWrestlers->contains($wrestlerB));
        $this->assertFalse($hiredWrestlers->contains($wrestlerC));
    }
}
