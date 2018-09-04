<?php

namespace Tests\Feature\Wrestler;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewWrestlerBioTest extends TestCase
{
    use RefreshDatabase;

    private $wrestler;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-wrestler');

        $this->wrestler = factory(Wrestler::class)->create([
            'name' => 'Wrestler 1',
            'slug' => 'wrestler1',
            'hired_at' => '2017-08-04',
            'hometown' => 'Kansas City, Missouri',
            'height' => 73,
            'weight' => 251,
            'signature_move' => 'Powerbomb',
        ]);
    }

    /** @test */
    public function users_who_have_permission_can_view_a_wrestler_bio()
    {
        $this->withoutExceptionHandling();
        $wrestler = $this->wrestler;
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertSuccessful();
        $response->assertViewIs('wrestlers.show');
        $response->assertViewHas('wrestler', function ($viewWrestler) use ($wrestler) {
            return $viewWrestler->id === $wrestler->id;
        });
    }

    /** @test */
    public function a_wrestlers_data_can_be_viewed_on_their_bio()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertSee('Wrestler 1');
        $response->assertSee('Kansas City, Missouri');
        $response->assertSee(e('6\'1"'));
        $response->assertSee('251 lbs.');
        $response->assertSee('Powerbomb');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_a_wrestler_bio()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_a_wrestler_bio()
    {
        $response = $this->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
