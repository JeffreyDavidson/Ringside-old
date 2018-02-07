<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Match;
use App\Models\Stipulation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StipulationTest extends TestCase
{
    use DatabaseMigrations;

    protected $stipulation;

    public function setUp()
    {
        parent::setUp();

        $this->stipulation = factory(Stipulation::class)->create();
    }

    /** @test */
    public function a_stipulation_belongs_to_matches()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->stipulation->matches);
    }
}
