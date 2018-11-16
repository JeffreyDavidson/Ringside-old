<?php

namespace Tests\Unit\Presenters;

use App\Models\Event;
use Tests\IntegrationTestCase;

class EventPresenterTest extends IntegrationTestCase
{
    /** @test */
    public function event_date_can_be_presented_as_formatted()
    {
        $event = factory(Event::class)->make(['date' => '2017-04-01 12:00:00']);

        $this->assertEquals('April 1st, 2017', $event->present()->date);
    }

    /** @test */
    public function event_date_can_be_presented_on_a_form_as_formatted()
    {
        $event = factory(Event::class)->make(['date' => '2017-04-01 12:00:00']);

        $this->assertEquals('04/01/2017', $event->present()->formattedFormDate);
    }

    /** @test */
    public function event_time_can_be_presented_as_formatted()
    {
        $event = factory(Event::class)->make(['date' => '2017-04-01 12:00:00']);

        $this->assertEquals('12:00pm', $event->present()->time);
    }
}
