<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wrestler;
use App\Models\WrestlerBio;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewWrestlerListTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function view_listing_of_active_wrestlers()
    {
        $user = factory(User::class)->create();
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $response = $this->actingAs($user)->get('wrestlers');

        $response->assertStatus(200);
        $response->data('wrestlers')->assertContains($wrestler);
    }

    /** @test */
    public function view_listing_of_inactive_wrestlers()
    {
        factory(Wrestler::class)->states('inactive')->create(['name' => 'Wrestler 1']);

        $this->get(route('wrestlers/inactive'));

        $this->see('Wrestler 1');
    }

    /** @test */
    public function view_listing_of_injured_wrestlers()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create(['name' => 'Wrestler 1']);

        $wrestler->injure();

        $this->get(route('wrestlers.injured'));

        $this->see('Wrestler 1');
    }

    /** @test */
    public function view_listing_of_suspended_wrestlers()
    {
        factory(Wrestler::class)->states('suspended')->create(['name' => 'Wrestler 1']);

        $this->visit('wrestlers/suspended');

        $this->see('Wrestler 1');
    }

    /** @test */
    public function view_listing_of_retired_wrestlers()
    {
        factory(Wrestler::class)->states('retired')->create(['name' => 'Wrestler 1']);

        $this->visit('wrestlers/retired');

        $this->see('Wrestler 1');
    }

}
