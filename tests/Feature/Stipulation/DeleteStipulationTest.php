<?php

namespace Tests\Feature\Stipulation;

use App\Models\Stipulation;
use Tests\IntegrationTestCase;

class DeleteStipulationTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('delete-stipulation');
    }

    /** @test */
    public function users_who_have_permission_can_delete_a_stipulation()
    {
        $stipulation = factory(Stipulation::class)->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('stipulations.index'))->delete(route('stipulations.destroy', $stipulation->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.index'));
        $this->assertSoftDeleted('stipulations', ['id' => $stipulation->id, 'name' => $stipulation->name]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_a_stipulation()
    {
        $stipulation = factory(Stipulation::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->from(route('stipulations.index'))->delete(route('stipulations.destroy', $stipulation->id));

        $response->assertStatus(403);
        $this->assertNull($stipulation->deleted_at);
    }

    /** @test */
    public function guests_cannot_delete_a_stipulation()
    {
        $stipulation = factory(Stipulation::class)->create();

        $response = $this->from(route('stipulations.index'))->delete(route('stipulations.destroy', $stipulation->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
