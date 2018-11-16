<?php

namespace Tests\Unit\Presenters;

use App\Models\Title;
use Tests\IntegrationTestCase;

class TitlePresenterTest extends IntegrationTestCase
{
    /** @test */
    public function a_titles_introduced_at_field_can_be_formatted()
    {
        $title = factory(Title::class)->make(['introduced_at' => '2017-09-17']);

        $this->assertEquals('September 17, 2017', $title->present()->introducedAt);
    }
}
