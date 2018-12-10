<?php

namespace Tests\Feature\Roster\Wrestler;

use Tests\IntegrationTestCase;
use App\Models\Roster\Wrestler;

class ViewWrestlerBioTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-roster-member');
    }

    /** @test */
    public function users_who_have_permission_can_view_a_wrestler_bio()
    {
        $this->withoutExceptionHandling();
        $wrestler = factory(Wrestler::class)->create([
            'name' => 'Wrestler 1',
            'slug' => 'wrestler1',
            'hired_at' => '2017-08-04',
            'hometown' => 'Kansas City, Missouri',
            'height' => 73,
            'weight' => 251,
            'signature_move' => 'Powerbomb',
        ]);

        $response = $this->actingAs($this->authorizedUser)->get(route('wrestlers.show', $wrestler->id));

        $response->assertSuccessful();
        $response->assertViewIs('wrestlers.show');
        $response->assertViewHas('wrestler', function ($viewWrestler) use ($wrestler) {
            return $viewWrestler->id === $wrestler->id;
        });
        $response->assertSee('Wrestler 1');
        $response->assertSee('Kansas City, Missouri');
        $response->assertSee(e('6\'1"'));
        $response->assertSee('251 lbs.');
        $response->assertSee('Powerbomb');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_a_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->get(route('wrestlers.show', $wrestler->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_a_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->create();

        $response = $this->get(route('wrestlers.show', $wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
