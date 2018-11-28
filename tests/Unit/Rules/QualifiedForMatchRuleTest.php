<?php

namespace Tests\Unit\Rules;

use App\Models\Event;
use App\Models\Title;
use App\Models\Wrestler;
use Tests\IntegrationTestCase;
use App\Rules\QualifiedForMatch;

class QualifiedForMatchRuleTest extends IntegrationTestCase
{
    /** @test */
    public function a_wrestler_with_a_hired_at_date_after_an_event_date_cannot_participate_in_the_match()
    {
        $wrestler = factory(Wrestler::class)->create(['hired_at' => '2017-10-10']);
        $event = factory(Event::class)->create(['date' => '2017-10-09']);

        $validator = new QualifiedForMatch($wrestler, 'hired_at', $event->date);

        $this->assertFalse($validator->passes('hired_at', $wrestler));
    }

    /** @test */
    public function a_wrestler_with_a_hired_at_date_before_or_equal_to_an_event_date_can_participate_in_the_match()
    {
        $wrestler = factory(Wrestler::class)->create(['hired_at' => '2017-10-08']);
        $event = factory(Event::class)->create(['date' => '2017-10-09']);

        $validator = new QualifiedForMatch($wrestler, 'hired_at', $event->date);

        $this->assertTrue($validator->passes('hired_at', $wrestler));
    }

    /** @test */
    public function a_title_with_a_introduced_at_date_after_an_event_date_cannot_be_involved_in_the_match()
    {
        $title = factory(Title::class)->create(['introduced_at' => '2017-10-10']);
        $event = factory(Event::class)->create(['date' => '2017-10-09']);

        $validator = new QualifiedForMatch($title, 'introduced_at', $event->date);

        $this->assertFalse($validator->passes('introduced_at', $title));
    }

    /** @test */
    public function a_title_with_a_introduced_at_date_before_or_equal_to_an_event_date_can_participate_in_the_match()
    {
        $title = factory(Title::class)->create(['introduced_at' => '2017-10-08']);
        $event = factory(Event::class)->create(['date' => '2017-10-09']);

        $validator = new QualifiedForMatch($title, 'introduced_at', $event->date);

        $this->assertTrue($validator->passes('introduced_at', $title));
    }
}
