<?php

namespace Tests\Unit;

use App\Models\Venue;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class VenueTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function venues_can_hold_many_event()
    {
        $venue = factory(Venue::class)->create();

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection', $venue->events
        );
    }
}
