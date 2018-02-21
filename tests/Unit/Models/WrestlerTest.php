<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Title;
use App\Models\Manager;
use App\Models\Wrestler;
use App\Models\WrestlerStatus;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class WrestlerTest extends TestCase
{
    use DatabaseMigrations;

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

    /** @test */
    public function a_wrestlers_status_can_be_returned()
    {
        $wrestler = factory(Wrestler::class)->create(['status_id' => 1]);

        $this->assertEquals(1, $this->wrestler->status());
    }
}
