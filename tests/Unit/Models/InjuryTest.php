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
    public function an_injury_is_healed_on_the_current_date()
    {
        $this->injury->heal();

        $this->assertEquals(Carbon::today()->toDateString(), $this->injury->healed_at->toDateString());
    }
}
