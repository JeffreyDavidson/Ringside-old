<?php

namespace Tests\Feature\Unit;

use App\Models\Event;
use App\Models\Wrestler;
use App\Rules\QualifiedForMatch;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class QualifiedForMatchRuleTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_wrestler_with_a_hired_at_date_after_an_event_date_cannot_participate()
    {
        $wrestler = factory(Wrestler::class)->create(['hired_at' => '2017-10-10']);
        $event = factory(Event::class)->create(['date' => '2017-10-09']);

        $validator = new QualifiedForMatch($event->date);

        $this->assertFalse($validator->passes(null, $wrestler->id));
        $this->assertEquals('This wrestler is not qualified for the match.', $validator->message());
    }

    /** @test */
    public function a_wrestler_with_a_hired_at_date_before_or_equal_to_an_event_date_can_participate()
    {
        $wrestler = factory(Wrestler::class)->create(['hired_at' => '2017-10-08']);
        $event = factory(Event::class)->create(['date' => '2017-10-09']);

        $validator = new QualifiedForMatch($event->date);

        $this->assertTrue($validator->passes(null, $wrestler->id));
    }
}
