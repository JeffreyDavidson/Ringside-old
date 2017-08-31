<?php

namespace Tests\Unit;

use App\Models\Wrestler;
use App\Models\WrestlerStatus;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HasStatusesTraitTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_wrestler_can_be_active()
    {
        $wrestler = factory(Wrestler::class)->create(['status_id' => 1]);

        $this->assertEquals(WrestlerStatus::ACTIVE, $wrestler->status());
    }

    /** @test */
    public function a_wrestler_can_be_inactive()
    {
        $wrestler = factory(Wrestler::class)->create(['status_id' => 2]);

        $this->assertEquals(WrestlerStatus::INACTIVE, $wrestler->status());
    }

    /** @test */
    public function it_can_get_all_active_users()
    {
        factory(Wrestler::class)->create(['status_id' => 1]);

        $this->assertEquals(1, Wrestler::active()->get()->count());
    }

    /** @test */
    public function it_can_get_all_inactive_users()
    {
        factory(Wrestler::class)->create(['status_id' => 2]);

        $this->assertEquals(1, Wrestler::inactive()->get()->count());
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
