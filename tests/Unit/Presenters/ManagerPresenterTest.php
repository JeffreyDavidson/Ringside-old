<?php

namespace Tests\Unit\Presenters;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManagerPresenterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_have_their_name_concatenated()
    {
        $manager = factory(Manager::class)->make(['first_name' => 'Michael', 'last_name' => 'Smith']);

        $this->assertEquals('Michael Smith', $manager->present()->fullName);
    }
}
