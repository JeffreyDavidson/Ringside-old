<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Injury;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InjuryTest extends TestCase
{
    use RefreshDatabase;

    protected $injury;

    public function setUp()
    {
        parent::setUp();

        $this->injury = factory(Injury::class)->create();
    }

    /** @test */
    public function an_injury_can_be_healed()
    {
        $this->injury->heal();

        $this->assertEquals(Carbon::now()->toDateString(), $this->injury->healed_at->toDateString());
    }
}
