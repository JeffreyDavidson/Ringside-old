<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Title;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TitleTest extends TestCase
{
    use DatabaseMigrations;

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
    public function a_title_belongs_to_matches()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->title->matches);
    }

    /** @test */
    public function a_title_can_set_a_new_champion()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->title->setNewChampion($wrestler, Carbon::now());

        $this->assertEquals($wrestler->id, $this->title->currentChampion->id);
    }

    /** @test */
    public function can_get_all_retired_titles()
    {
        $retiredTitleA = factory(Title::class)->create(['retired_at' => Carbon::yesterday()]);
        $retiredTitleB = factory(Title::class)->create(['retired_at' => Carbon::yesterday()]);
        $activeTitle = factory(Title::class)->create(['retired_at' => NULL]);

        $retiredTitles = Title::retired()->get();

        $this->assertTrue($retiredTitles->contains($retiredTitleA));
        $this->assertTrue($retiredTitles->contains($retiredTitleB));
        $this->assertFalse($retiredTitles->contains($activeTitle));
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
}
