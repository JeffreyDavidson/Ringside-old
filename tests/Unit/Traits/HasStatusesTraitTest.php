<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Wrestler;
use App\Models\WrestlerStatus;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HasStatusesTraitTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_wrestler_can_be_active()
    {
        $wrestler = factory(Wrestler::class)->create(['status_id' => 1]);

        $this->assertTrue($wrestler->isActive());
    }

    /** @test */
    public function a_wrestler_can_be_inactive()
    {
        $wrestler = factory(Wrestler::class)->create(['status_id' => 2]);

        $this->assertTrue($wrestler->isInactive());
    }

    /** @test */
    public function it_can_get_all_active_users()
    {
        factory(Wrestler::class, 3)->create(['status_id' => WrestlerStatus::ACTIVE]);

        $this->assertEquals(3, Wrestler::active()->get()->count());
    }

    /** @test */
    public function it_can_get_all_inactive_users()
    {
        factory(Wrestler::class, 3)->create(['status_id' => WrestlerStatus::INACTIVE]);

        $this->assertEquals(3, Wrestler::inactive()->get()->count());
    }

    /** @test */
    public function it_can_retrieve_all_injured_wrestlers()
    {
        factory(Wrestler::class, 3)->create(['status_id' => WrestlerStatus::INJURED]);

        $this->assertEquals(3, Wrestler::injured()->get()->count());
    }

    /** @test */
    public function it_can_retrieve_all_retired_wrestlers()
    {
        factory(Wrestler::class, 3)->create(['status_id' => WrestlerStatus::RETIRED]);

        $this->assertEquals(3, Wrestler::retired()->get()->count());
    }

    /** @test */
    public function it_can_retrieve_all_suspended_wrestlers()
    {
        factory(Wrestler::class, 3)->create(['status_id' => WrestlerStatus::SUSPENDED]);

        $this->assertEquals(3, Wrestler::suspended()->get()->count());
    }

    /** @test */
    public function it_can_change_a_users_active_status_to_inactive()
    {
        $wrestler = factory(Wrestler::class)->create(['status_id' => 1]);

        $wrestler->setStatusToInactive();

        $this->assertEquals(WrestlerStatus::INACTIVE, $wrestler->status());
    }

    /** @test */
    public function it_can_change_a_users_inactive_status_to_active()
    {
        $wrestler = factory(Wrestler::class)->create(['status_id' => 2]);

        $wrestler->setStatusToActive();

        $this->assertEquals(WrestlerStatus::ACTIVE, $wrestler->status());
    }
}