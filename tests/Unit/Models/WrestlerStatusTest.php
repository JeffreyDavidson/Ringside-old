<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\WrestlerStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WrestlerStatusTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_get_available_options_for_an_active_status()
    {
        $options = WrestlerStatus::getAvailableOptions('Active');

        $this->assertTrue($options->contains('Active'));
        $this->assertTrue($options->contains('Inactive'));
        $this->assertTrue($options->contains('Injured'));
        $this->assertTrue($options->contains('Suspended'));
        $this->assertTrue($options->contains('Retired'));
    }

    /** @test */
    public function it_can_get_available_options_for_an_inactive_status()
    {
        $options = WrestlerStatus::getAvailableOptions('Inactive');

        $this->assertTrue($options->contains('Active'));
        $this->assertTrue($options->contains('Inactive'));
        $this->assertFalse($options->contains('Injured'));
        $this->assertFalse($options->contains('Suspended'));
        $this->assertFalse($options->contains('Retired'));
    }

    /** @test */
    public function it_can_get_available_options_for_an_injured_status()
    {
        $options = WrestlerStatus::getAvailableOptions('Injured');

        $this->assertTrue($options->contains('Active'));
        $this->assertTrue($options->contains('Inactive'));
        $this->assertTrue($options->contains('Injured'));
        $this->assertFalse($options->contains('Suspended'));
        $this->assertTrue($options->contains('Retired'));
    }

    /** @test */
    public function it_can_get_available_options_for_a_suspended_status()
    {
        $options = WrestlerStatus::getAvailableOptions('Suspended');

        $this->assertTrue($options->contains('Active'));
        $this->assertTrue($options->contains('Inactive'));
        $this->assertFalse($options->contains('Injured'));
        $this->assertTrue($options->contains('Suspended'));
        $this->assertTrue($options->contains('Retired'));
    }

    /** @test */
    public function it_can_get_available_options_for_a_retired_status()
    {
        $options = WrestlerStatus::getAvailableOptions('Retired');

        $this->assertTrue($options->contains('Active'));
        $this->assertTrue($options->contains('Inactive'));
        $this->assertFalse($options->contains('Injured'));
        $this->assertFalse($options->contains('Suspended'));
        $this->assertTrue($options->contains('Retired'));
    }
}
