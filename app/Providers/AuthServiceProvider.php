<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Title;
use App\Models\Venue;
use App\Models\Wrestler;
use App\Models\Stipulation;
use App\Policies\EventPolicy;
use App\Policies\TitlePolicy;
use App\Policies\VenuePolicy;
use App\Policies\WrestlerPolicy;
use App\Policies\StipulationPolicy;
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
        Event::class => EventPolicy::class,
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
