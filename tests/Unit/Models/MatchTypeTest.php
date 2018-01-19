<?php

namespace Tests\Unit;

use App\Models\MatchType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MatchTypeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_battle_royal_match_type_requires_multiple_referees()
    {
        $matchType = factory(MatchType::class)->create(['slug' => 'battleroyal']);

        $this->assertTrue($matchType->needsMultipleReferees());
    }

    /** @test */
    public function a_royal_rumble_match_type_requires_multiple_referees()
    {
        $matchType = factory(MatchType::class)->create(['slug' => 'royalrumble']);
        
        $this->assertTrue($matchType->needsMultipleReferees());
    }
}
