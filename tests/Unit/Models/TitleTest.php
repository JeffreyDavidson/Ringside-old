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
    public function can_get_all_valid_titles_for_an_event()
    {
        $validTitleA = factory(Title::class)->create(['introduced_at' => Carbon::parse('3 weeks ago')]);
        $validTitleB = factory(Title::class)->create(['introduced_at' => Carbon::parse('2 weeks ago')]);
        $invalidTitle = factory(Title::class)->create(['introduced_at' => Carbon::parse('next week')]);
        $event = factory(Event::class)->create(['date' => Carbon::parse('tomorrow')]);

        $validTitles = Title::valid($event->date)->get();

        $this->assertTrue($validTitles->contains($validTitleA));
        $this->assertTrue($validTitles->contains($validTitleB));
        $this->assertFalse($validTitles->contains($invalidTitle));
    }

    /** @test */
    public function a_title_can_set_a_new_champion()
    {
        $title = factory(Title::class)->create();
        $wrestler = factory(Wrestler::class)->create();

        $title->setNewChampion($wrestler, Carbon::now());

        $this->assertEquals($wrestler->id, $title->getCurrentChampion()->id);
    }
}
