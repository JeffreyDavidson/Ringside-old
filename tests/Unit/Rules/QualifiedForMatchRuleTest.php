<?php

namespace Tests\Unit\Rules;

use App\Models\Event;
use Tests\TestCase;
use App\Models\Wrestler;
use App\Models\Title;
use App\Rules\QualifiedForMatch;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QualifiedForMatchRuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_wrestler_with_a_hired_at_date_after_an_event_date_cannot_participate_in_the_match()
    {
        $wrestler = factory(Wrestler::class)->create(['hired_at' => '2017-10-10']);
        $event = factory(Event::class)->create(['date' => '2017-10-09']);

        $validator = new QualifiedForMatch($event->date, $wrestler);

        $this->assertFalse($validator->passes('hired_at', $wrestler));
        $this->assertEquals($validator->message(), 'This wrestler is not qualified for the match.');
    }

    /** @test */
    public function a_wrestler_with_a_hired_at_date_before_or_equal_to_an_event_date_can_participate_in_the_match()
    {
        $wrestler = factory(Wrestler::class)->create(['hired_at' => '2017-10-08']);
        $event = factory(Event::class)->create(['date' => '2017-10-09']);

        $validator = new QualifiedForMatch($event->date, $wrestler);

        $this->assertTrue($validator->passes('hired_at', $wrestler));
    }

    /** @test */
    public function a_title_with_a_introduced_at_date_after_an_event_date_cannot_be_involved_in_the_match()
    {
        $title = factory(Title::class)->create(['introduced_at' => '2017-10-10']);
        $event = factory(Event::class)->create(['date' => '2017-10-09']);

        $validator = new QualifiedForMatch($event->date, $title);

        $this->assertFalse($validator->passes('introduced_at', $title));
        $this->assertEquals($validator->message(), 'This title is not qualified for the match.');
    }

    /** @test */
    public function a_title_with_a_introduced_at_date_before_or_equal_to_an_event_date_can_participate_in_the_match()
    {
        $title = factory(Title::class)->create(['introduced_at' => '2017-10-08']);
        $event = factory(Event::class)->create(['date' => '2017-10-09']);

        $validator = new QualifiedForMatch($event->date, $title);

        $this->assertTrue($validator->passes('introduced_at', $title));
    }
}
