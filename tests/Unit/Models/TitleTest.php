<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Title;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TitleTest extends TestCase
{
    use RefreshDatabase;

    protected $title;

    public function setUp()
    {
        parent::setUp();

        $this->title = factory(Title::class)->create();
    }

    /** @test */
    public function a_title_has_many_champions()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->title->champions);
    }

    /** @test */
    public function a_title_belongs_to_many_matches()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->title->matches);
    }

    /** @test */
    public function a_title_has_many_retirements()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->title->retirements);
    }

    /** @test */
    public function a_title_can_set_a_champion()
    {
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();
        $this->title->setChampion($wrestlerA, Carbon::yesterday());
        $this->assertEquals($this->title->currentChampion, $wrestlerA);
        $this->title->refresh();

        $this->title->setChampion($wrestlerB, Carbon::today());

        $this->assertEquals($this->title->currentChampion->id, $wrestlerB->id);
    }
}
