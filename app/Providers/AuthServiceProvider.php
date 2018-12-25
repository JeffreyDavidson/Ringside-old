<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Match;
use App\Models\Title;
use App\Models\Venue;
use App\Models\Stipulation;
use App\Policies\EventPolicy;
use App\Policies\MatchPolicy;
use App\Policies\TitlePolicy;
use App\Policies\VenuePolicy;
use App\Models\Roster\TagTeam;
use App\Models\Roster\Wrestler;
use App\Policies\StipulationPolicy;
use App\Policies\RosterMemberPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Wrestler::class => RosterMemberPolicy::class,
        TagTeam::class => RosterMemberPolicy::class,
        Event::class => EventPolicy::class,
        Match::class => MatchPolicy::class,
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
