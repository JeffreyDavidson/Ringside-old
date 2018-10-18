<?php

namespace Tests\Feature\Title\Inactive;

use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivateInactiveTitleTest extends TestCase
{
    use RefreshDatabase;

    private $title;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('activate-title');

        $this->title = factory(Title::class)->states('inactive')->create();
    }

    /** @test */
    public function users_who_have_permission_can_activate_an_inactive_title()
    {
        // $this->withoutExceptionHandling();
        // dd($this->title);
        $response = $this->actingAs($this->authorizedUser)
            ->from(route('inactive-titles.index'))
            ->post(route('inactive-titles.activate', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('inactive-titles.index'));
        $this->assertTrue($this->title->fresh()->is_active);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_activate_an_inactive_title()
    {
        $response = $this->actingAs($this->unauthorizedUser)
            ->from(route('inactive-titles.index'))
            ->post(route('inactive-titles.activate', $this->title->id));

        $response->assertStatus(403);
        $this->assertFalse($this->title->is_active);
    }

    /** @test */
    public function guests_cannot_activate_an_inactive_title()
    {
        $response = $this->from(route('inactive-titles.index'))
            ->post(route('inactive-titles.activate', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
