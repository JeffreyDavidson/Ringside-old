<?php

namespace Tests\Feature\Stipulation;

use Tests\TestCase;
use App\Models\Stipulation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteStipulationTest extends TestCase
{
    use RefreshDatabase;

    private $stipulation;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('delete-stipulation');

        $this->stipulation = factory(Stipulation::class)->create();
    }

    /** @test */
    public function users_who_have_permission_can_delete_a_stipulation()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('stipulations.index'))
                        ->delete(route('stipulations.destroy', $this->stipulation->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.index'));
        $this->assertSoftDeleted('stipulations', ['id' => $this->stipulation->id, 'name' => $this->stipulation->name]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_a_stipulation()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('stipulations.index'))
                        ->delete(route('stipulations.destroy', $this->stipulation->id));

        $response->assertStatus(403);
        $this->assertNull($this->stipulation->deleted_at);
    }

    /** @test */
    public function guests_cannot_delete_a_stipulation()
    {
        $response = $this->from(route('stipulations.index'))
                        ->delete(route('stipulations.destroy', $this->stipulation->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
