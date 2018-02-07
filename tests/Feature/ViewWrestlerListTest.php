<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewWrestlerListTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-wrestlers');
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_wrestlers()
    {
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();
        $wrestlerC = factory(Wrestler::class)->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('wrestlers.index'));

        $response->assertStatus(200);
        $response->data('wrestlers')->assertEquals([
            $wrestlerA,
            $wrestlerB,
            $wrestlerC,
        ]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_wrestlers()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('wrestlers.index'));

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
