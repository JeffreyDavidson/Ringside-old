<?php

namespace Tests\Unit\Presenters;

use App\Models\Roster\Manager;
use Tests\IntegrationTestCase;

class ManagerPresenterTest extends IntegrationTestCase
{
    /** @test */
    public function a_managers_name_can_be_concatenated()
    {
        $manager = factory(Manager::class)->make(['first_name' => 'Michael', 'last_name' => 'Smith']);

        $this->assertEquals('Michael Smith', $manager->present()->fullName);
    }
}
