<?php

namespace Tests\Feature\Title\Retired;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnretireRetiredTitleTest extends TestCase
{
    use RefreshDatabase;

    private $title;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('unretire-title');

        $this->title = factory(Title::class)->states('retired')->create();
    }

    /** @test */
    public function users_who_have_permission_can_unretire_a_retired_title()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('retired-titles.index'))
                        ->delete(route('retired-titles.unretire', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('retired-titles.index'));
        $this->assertNotNull($this->title->retirements->first()->ended_at);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_unretire_a_retired_title()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('retired-titles.index'))
                        ->delete(route('retired-titles.unretire', $this->title->id));

        $response->assertStatus(403);
        $this->assertNull($this->title->retirements->first()->ended_at);
    }

    /** @test */
    public function guests_cannot_unretire_a_retired_title()
    {
        $response = $this->from(route('retired-titles.index'))
                        ->delete(route('retired-titles.unretire', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
