<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\MatchType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MatchTypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_battle_royal_and_royal_rumble_match_types_requires_multiple_referees()
    {
        $matchTypeA = factory(MatchType::class)->create(['slug' => 'battleroyal']);
        $matchTypeB = factory(MatchType::class)->create(['slug' => 'royalrumble']);

        $this->assertTrue($matchTypeA->needsMultipleReferees());
        $this->assertTrue($matchTypeB->needsMultipleReferees());
    }
}
