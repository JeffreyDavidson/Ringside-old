<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TitleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_title_has_many_championships()
    {
        $title = factory(Title::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $title->champions);
    }

    /** @test */
    public function a_title_belongs_to_many_matches()
    {
        $title = factory(Title::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $title->matches);
    }

    /** @test */
    public function a_title_has_many_retirements()
    {
        $title = factory(Title::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $title->retirements);
    }

    /** @test */
    public function an_active_title_can_be_retired()
    {
        $title = factory(Title::class)->states('active')->create();

        $title->retire();

        $this->assertEquals(1, $title->retirements->count());
        $this->assertFalse($title->is_active);
        $this->assertTrue($title->isRetired());
        $this->assertNull($title->retirements()->first()->ended_at);
    }

    /** @test */
    public function a_retired_title_can_be_unretired()
    {
        $title = factory(Title::class)->states('retired')->create();

        $title->unretire();

        $this->assertNotNull($title->retirements()->first()->ended_at);
        $this->assertTrue($title->is_active);
        $this->assertFalse($title->isRetired());
    }

    /** @test */
    public function it_can_get_retired_titles()
    {
        $titleA = factory(Title::class)->states('retired')->create();
        $titleB = factory(Title::class)->states('retired')->create();
        $titleC = factory(Title::class)->states('active')->create();

        $retiredTitles = Title::retired()->get();

        $this->assertTrue($retiredTitles->contains($titleA));
        $this->assertTrue($retiredTitles->contains($titleB));
        $this->assertFalse($retiredTitles->contains($titleC));
    }

    /**
     * @expectedException \App\Exceptions\ModelAlreadyRetiredException
     *
     * @test
     */
    public function a_retired_title_cannot_be_retired()
    {
        $title = factory(Title::class)->states('retired')->create();

        $title->retire();
    }

    /**
     * @expectedException \App\Exceptions\ModelNotRetiredException
     *
     * @test
     */
    public function an_active_title_cannot_be_unretired()
    {
        $title = factory(Title::class)->states('active')->create();

        $title->unretire();
    }
}
