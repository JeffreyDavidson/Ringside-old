<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Match;
use App\Models\Stipulation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StipulationTest extends TestCase
{
    use RefreshDatabase;

    protected $stipulation;

    public function setUp()
    {
        parent::setUp();

        $this->stipulation = factory(Stipulation::class)->create();
    }

    /** @test */
    public function a_stipulation_has_many_matches()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->stipulation->matches);
    }
}
