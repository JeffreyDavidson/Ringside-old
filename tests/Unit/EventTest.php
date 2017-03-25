<?php

namespace Tests\Unit;

use App\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_have_matches()
    {
        $event = create(Event::class);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $event->matches);
    }

}
