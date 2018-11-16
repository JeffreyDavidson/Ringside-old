<?php

namespace Tests\Unit\Presenters;

use Tests\TestCase;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RefereePresenterTest extends TestCase
{
    /** @test */
    public function a_referees_name_can_be_concatenated()
    {
        $referee = factory(Referee::class)->make(['first_name' => 'Michael', 'last_name' => 'Smith']);

        $this->assertEquals('Michael Smith', $referee->present()->fullName);
    }
}
