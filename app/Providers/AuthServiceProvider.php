<?php

namespace App\Providers;

use App\Models\Wrestler;
use App\Policies\WrestlerPolicy;
use App\Models\Title;
use App\Policies\TitlePolicy;
use App\Models\Stipulation;
use App\Policies\StipulationPolicy;
use App\Models\Venue;
use App\Policies\VenuePolicy;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Wrestler::class => WrestlerPolicy::class,
        Title::class => TitlePolicy::class,
        Stipulation::class => StipulationPolicy::class,
        Venue::class => VenuePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
