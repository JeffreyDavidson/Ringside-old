<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TitlePresenterTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_title_can_have_their_introduced_at_field_formatted()
    {
        $title = factory(Title::class)->make(['introduced_at' => '2017-09-17']);

        $this->assertEquals('September 17, 2017', $title->present()->introducedAt);
    }

    /** @test */
    public function a_title_can_have_their_retired_at_field_formatted()
    {
        $title = factory(Title::class)->make(['retired_at' => '2017-09-17']);

        $this->assertEquals('September 17, 2017', $title->present()->retiredAt);
    }
}
