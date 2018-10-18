<?php

namespace Tests\Feature\Title\Active;

use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeactivateActiveTitleTest extends TestCase
{
    use RefreshDatabase;

    private $title;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('deactivate-title');

        $this->title = factory(Title::class)->states('active')->create();
    }

    /** @test */
    public function users_who_have_permission_can_deactivate_an_active_title()
    {
        $response = $this->actingAs($this->authorizedUser)
            ->from(route('active-titles.index'))
            ->delete(route('active-titles.deactivate', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('active-titles.index'));
        $this->assertFalse($this->title->fresh()->is_active);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_deactivate_an_active_title()
    {
        $response = $this->actingAs($this->unauthorizedUser)
            ->from(route('active-titles.index'))
            ->delete(route('active-titles.deactivate', $this->title->id));

        $response->assertStatus(403);
        $this->assertTrue($this->title->is_active);
    }

    /** @test */
    public function guests_cannot_deactivate_an_active_title()
    {
        $response = $this->from(route('active-titles.index'))
            ->delete(route('active-titles.deactivate', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
