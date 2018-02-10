<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Injury;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class InjuryTest extends TestCase
{
    use DatabaseMigrations;

    protected $injury;

    public function setUp()
    {
        parent::setUp();

        $this->injury = factory(Injury::class)->create();
    }

    /** @test */
    public function an_injury_can_be_healed_on_the_current_dte()
    {
        $this->injury->heal();

        $this->assertEquals(Carbon::today()->toDateString(), $this->injury->healed_at->toDateString());
    }

    /** @test */
    public function an_injury_can_be_lifted_on_any_given_day()
    {
        $this->injury->heal(Carbon::parse('2018-02-01'));

        $this->assertEquals('2018-02-01', $this->injury->healed_at->toDateString());
    }
}
