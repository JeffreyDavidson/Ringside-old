<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Wrestler;
use App\Models\Title;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasRetirementsTraitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_wrestler_can_retire()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->retire();

        $this->assertEquals(1, $wrestler->retirements->count());
        $this->assertTrue($wrestler->isInactive());
        $this->assertTrue($wrestler->isRetired());
        $this->assertNull($wrestler->retirements()->first()->ended_at);
    }

    /** @test */
    public function a_retired_wrestler_can_unretire()
    {
        $wrestler = factory(Wrestler::class)->create()->retire();

        $wrestler->unretire();

        $this->assertNotNull($wrestler->retirements()->first()->ended_at);
        $this->assertTrue($wrestler->isActive());
        $this->assertFalse($wrestler->isRetired());
    }

    /** @test */
    public function a_wrestler_can_have_multiple_retirements()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->retire();
        $wrestler->unretire();
        $wrestler->retire();

        $this->assertTrue($wrestler->hasPastRetirements());
        $this->assertEquals(1, $wrestler->pastRetirements->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerAlreadyRetiredException
     *
     * @test
     */
    public function a_retired_wrestler_cannot_retire()
    {
        $wrestler = factory(Wrestler::class)->create()->retire();

        $wrestler->retire();

        $this->assertEquals(1, $wrestler->retirements->count());

    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotRetiredException
     *
     * @test
     */
    public function a_wrestler_who_is_not_retired_cannot_unretire()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->unretire();

        $this->assertEquals(0, $wrestler->retirements->count());
    }

    /** @test */
    public function a_title_can_be_retired()
    {
        $title = factory(Title::class)->create();

        $title->retire();

        $this->assertEquals(1, $title->retirements->count());
        $this->assertTrue($title->isInactive());
        $this->assertTrue($title->isRetired());
        $this->assertNull($title->retirements()->first()->ended_at);
    }
}