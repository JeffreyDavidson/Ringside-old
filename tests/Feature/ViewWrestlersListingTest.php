<?php

namespace Tests\Feature;

use App\Wrestler;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewWrestlersListingTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function view_listing_of_active_wrestlers()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $this->visit('wrestlers/active');

        $this->see($wrestler->name);
    }

    /** @test */
    public function view_listing_of_inactive_wrestlers()
    {
        $wrestler = factory(Wrestler::class)->states('inactive')->create();

        $this->visit('wrestlers/inactive');

        $this->see($wrestler->name);
    }

    /** @test */
    public function view_listing_of_injured_wrestlers()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->injure();

        $this->visit('wrestlers/injured');

        $this->see($wrestler->name);
    }

    /** @test */
    public function view_listing_of_suspended_wrestlers()
    {
        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $this->visit('wrestlers/suspended');

        $this->see($wrestler->name);
    }

    /** @test */
    public function view_listing_of_retired_wrestlers()
    {
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $this->visit('wrestlers/retired');

        $this->see($wrestler->name);
    }

}
