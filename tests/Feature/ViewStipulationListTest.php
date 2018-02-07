<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Stipulation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewStipulationListTest extends TestCase
{
    use DatabaseMigrations;

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

        $response->assertStatus(200);
        $response->assertViewIs('stipulations.index');
        $response->data('stipulations')->assertEquals([
            $stipulationA,
            $stipulationB,
            $stipulationC,
        ]);
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
