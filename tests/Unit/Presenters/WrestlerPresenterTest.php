<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WrestlerPresenterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_wrestler_can_have_their_height_formatted()
    {
        $wrestler = factory(Wrestler::class)->make(['height' => '73']);

        $this->assertEquals('6\'1"', $wrestler->present()->height);
    }
}
