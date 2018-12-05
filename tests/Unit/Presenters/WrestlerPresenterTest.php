<?php

namespace Tests\Unit\Presenters;

use App\Models\Roster\Wrestler;
use Tests\IntegrationTestCase;

class WrestlerPresenterTest extends IntegrationTestCase
{
    /** @test */
    public function a_wrestlers_height_can_be_formatted()
    {
        $wrestler = factory(Wrestler::class)->make(['height' => '73']);

        $this->assertEquals('6\'1"', $wrestler->present()->height);
    }
}
