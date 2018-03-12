<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Wrestler;
use App\Models\WrestlerStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasStatusesTraitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_wrestler_with_a_status_id_of_one_is_active()
    {
        $wrestler = factory(Wrestler::class)->create(['status_id' => 1]);

        $this->assertTrue($wrestler->isActive());
    }

    /** @test */
    public function a_wrestler_with_a_status_id_of_two_is_inactive()
    {
        $wrestler = factory(Wrestler::class)->create(['status_id' => 2]);

        $this->assertTrue($wrestler->isInactive());
    }

    /** @test */
    public function it_can_get_all_active_users()
    {
        factory(Wrestler::class, 3)->states('active')->create();

        $this->assertEquals(3, Wrestler::hasStatus('Active')->get()->count());
    }

    /** @test */
    public function it_can_get_all_inactive_users()
    {
        factory(Wrestler::class, 3)->states('inactive')->create();

        $this->assertEquals(3, Wrestler::hasStatus('Inactive')->get()->count());
    }

    /** @test */
    public function it_can_retrieve_all_injured_wrestlers()
    {
        factory(Wrestler::class, 3)->states('injured')->create();

        $this->assertEquals(3, Wrestler::hasStatus('Injured')->get()->count());
    }

    /** @test */
    public function it_can_retrieve_all_retired_wrestlers()
    {
        factory(Wrestler::class, 3)->states('retired')->create();

        $this->assertEquals(3, Wrestler::hasStatus('Retired')->get()->count());
    }

    /** @test */
    public function it_can_retrieve_all_suspended_wrestlers()
    {
        factory(Wrestler::class, 3)->states('suspended')->create();

        $this->assertEquals(3, Wrestler::hasStatus('Suspended')->get()->count());
    }

    /** @test */
    public function it_can_change_a_users_active_status_to_inactive()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->setStatusToInactive();

        $this->assertEquals(2, $wrestler->status_id);
    }

    /** @test */
    public function it_can_change_a_users_inactive_status_to_active()
    {
        $wrestler = factory(Wrestler::class)->create(['status_id' => 2]);

        $wrestler->setStatusToActive();

        $this->assertEquals(1, $wrestler->status_id);
    }
}