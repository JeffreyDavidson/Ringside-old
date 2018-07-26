<?php

namespace Tests\Feature\Stipulation;

use Tests\TestCase;
use App\Models\Stipulation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewStipulationListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-stipulations');
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_stipulations()
    {
        $stipulationA = factory(Stipulation::class)->create();
        $stipulationB = factory(Stipulation::class)->create();
        $stipulationC = factory(Stipulation::class)->create();

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('stipulations.index'));

        $response->assertSuccessful();
        $response->assertViewIs('stipulations.index');
        $response->data('stipulations')->assertContains($stipulationA);
        $response->data('stipulations')->assertContains($stipulationB);
        $response->data('stipulations')->assertContains($stipulationC);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_stipulations()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('stipulations.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_stipulation_list()
    {
        $response = $this->get(route('stipulations.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
