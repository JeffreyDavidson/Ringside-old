<?php

namespace Tests\Unit\Presenters;

use App\Models\Retirement;
use Tests\IntegrationTestCase;

class RetirementPresenterTest extends IntegrationTestCase
{
    /** @test */
    public function a_retirement_can_have_its_retired_at_field_formatted()
    {
        $retirement = factory(Retirement::class)->create(['retired_at' => '2017-09-17']);

        $this->assertEquals('September 17, 2017', $retirement->present()->retiredAt);
    }
}
