<?php

namespace Tests\Unit\Presenters;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TitlePresenterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_title_can_have_its_introduced_at_field_formatted()
    {
        $title = factory(Title::class)->make(['introduced_at' => '2017-09-17']);

        $this->assertEquals('September 17, 2017', $title->present()->introducedAt);
    }

    /** @test */
    public function a_title_can_have_their_retired_at_field_formatted()
    {
        $title = factory(Title::class)->make();
        $title->retire();

        dd($title->retirements);

        $this->assertEquals('September 17, 2017', $title->present()->retiredAt);
    }
}
