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
    public function an_active_wrestler_can_retire()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->retire();

        $this->assertEquals(1, $wrestler->retirements->count());
        $this->assertFalse($wrestler->is_active);
        $this->assertTrue($wrestler->isRetired());
        $this->assertNull($wrestler->retirements()->first()->ended_at);
    }

    /** @test */
    public function a_retired_wrestler_can_unretire()
    {
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $wrestler->unretire();

        $this->assertNotNull($wrestler->retirements()->first()->ended_at);
        $this->assertTrue($wrestler->is_active);
        $this->assertFalse($wrestler->isRetired());
    }

    /** @test */
    public function a_wrestler_can_have_many_retirements()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $wrestler->retirements);
    }

    /** @test */
    public function it_can_get_retired_wrestlers()
    {
        $wrestlerA = factory(Wrestler::class)->states('retired')->create();
        $wrestlerB = factory(Wrestler::class)->states('retired')->create();
        $wrestlerC = factory(Wrestler::class)->states('active')->create();

        $retiredWrestlers = Wrestler::retired()->get();

        $this->assertTrue($retiredWrestlers->contains($wrestlerA));
        $this->assertTrue($retiredWrestlers->contains($wrestlerB));
        $this->assertFalse($retiredWrestlers->contains($wrestlerC));
    }

    /**
     * @expectedException \App\Exceptions\ModelAlreadyRetiredException
     *
     * @test
     */
    public function a_retired_wrestler_cannot_retire()
    {
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $wrestler->retire();
    }

    /**
     * @expectedException \App\Exceptions\ModelNotRetiredException
     *
     * @test
     */
    public function an_active_wrestler_cannot_unretire()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->unretire();
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
    public function a_title_can_have_many_retirements()
    {
        $title = factory(Title::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $title->retirements);
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
    public function a_retired_title_cannot_retire()
    {
        $title = factory(Title::class)->states('retired')->create();

        $title->retire();
    }

    /**
     * @expectedException \App\Exceptions\ModelNotRetiredException
     *
     * @test
     */
    public function an_active_title_cannot_unretire()
    {
        $title = factory(Title::class)->states('active')->create();

        $title->unretire();
    }
}