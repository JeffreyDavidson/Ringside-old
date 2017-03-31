<?php

namespace Tests\Unit;

use App\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EventTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_have_matches()
    {
        $event = factory(Event::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $event->matches);
    }

}
