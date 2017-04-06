<?php

namespace Tests\Unit;

use App\Match;
use App\MatchType;
use App\Title;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MatchTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_match_must_have_a_type()
    {
        $type = factory(MatchType::class)->create();
        $match = factory(Match::class)->create(['match_type_id' => $type->id]);

        $this->assertEquals($match->type->id, $type->id);
    }

    /** @test */
    public function a_match_can_have_a_title_competed_in_it()
    {
        $titles = factory(Title::class, 2)->create();
        $match = factory(Match::class)->create();

        $match->addTitles($titles);

        $this->assertCount(2, $match->titles);
    }
}