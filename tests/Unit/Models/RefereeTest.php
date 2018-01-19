<?php

namespace Tests\Unit;

use App\Models\Referee;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RefereeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_have_their_name_concatenated()
    {
        $referee = factory(Referee::class)->make(['first_name' => 'Jeffrey', 'last_name' => 'Davidson']);

        $this->assertEquals('Jeffrey Davidson', $referee->present()->fullName);
    }
}
