<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Stipulation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewStipulationTest extends TestCase
{
    use DatabaseMigrations;

    private $stipulation;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('show-stipulation');

        $this->stipulation = factory(Stipulation::class)->create([
            'name' => 'Stipulation Name',
            'slug' => 'stipulation-slug',
        ]);
    }

    /** @test */
    public function users_who_have_permission_can_view_a_stipulation()
    {
        $response = $this->actingAs($this->authorizedUser)->get(route('stipulations.show', $this->stipulation->id));

        $response->assertSuccessful();
        $response->assertViewIs('stipulations.show');
        $response->assertViewHas('stipulation');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_a_stipulation()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('stipulations.show', $this->stipulation->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_a_stipulation()
    {
        $response = $this->get(route('stipulations.show', $this->stipulation->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
