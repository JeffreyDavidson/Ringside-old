<?php

namespace Tests\Feature\Wrestler;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewWrestlerListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-wrestlers');
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_wrestlers()
    {
        factory(Wrestler::class)->create(['name' => 'Wrestler A']);
        factory(Wrestler::class)->create(['name' => 'Wrestler B']);
        factory(Wrestler::class)->create(['name' => 'Wrestler C']);

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.index'));

        $response->assertSuccessful();
        $response->assertViewIs('wrestlers.index');
        $response->assertSee('Wrestler A');
        $response->assertSee('Wrestler B');
        $response->assertSee('Wrestler C');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_wrestlers()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('wrestlers.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_wrestler_list()
    {
        $response = $this->get(route('wrestlers.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
