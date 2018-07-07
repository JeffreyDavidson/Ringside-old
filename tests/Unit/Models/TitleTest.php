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

    /** @test */
    public function can_get_all_active_titles()
    {
        $activeTitleA = factory(Title::class)->create(['retired_at' => NULL, 'introduced_at' => Carbon::yesterday()]);
        $activeTitleB = factory(Title::class)->create(['retired_at' => NULL, 'introduced_at' => Carbon::yesterday()]);
        $retiredTitle = factory(Title::class)->create(['retired_at' => Carbon::yesterday(), 'introduced_at' => Carbon::tomorrow()]);
        $inactiveTitle = factory(Title::class)->create(['retired_at' => NULL, 'introduced_at' => Carbon::tomorrow()]);

        $activeTitles = Title::active(Carbon::today())->get();

        $this->assertTrue($activeTitles->contains($activeTitleA));
        $this->assertTrue($activeTitles->contains($activeTitleB));
        $this->assertFalse($activeTitles->contains($retiredTitle));
        $this->assertFalse($activeTitles->contains($inactiveTitle));
    }

    /** @test */
    public function can_get_all_retired_titles()
    {
        $retiredTitleA = factory(Title::class)->create()->retire();
        $retiredTitleB = factory(Title::class)->create()->retire();
        $activeTitle = factory(Title::class)->create();

        $retiredTitles = Title::retired()->get();

        $this->assertTrue($retiredTitles->contains($retiredTitleA));
        $this->assertTrue($retiredTitles->contains($retiredTitleB));
        $this->assertFalse($retiredTitles->contains($activeTitle));
    }
}
