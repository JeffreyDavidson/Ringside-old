<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RefereePresenterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_have_their_name_concatenated()
    {
        $referee = factory(Referee::class)->make(['first_name' => 'Michael', 'last_name' => 'Smith']);

        $this->assertEquals('Michael Smith', $referee->present()->fullName);
    }
}
