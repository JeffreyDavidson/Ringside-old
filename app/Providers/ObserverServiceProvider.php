<?php

namespace App\Providers;

use App\Models\Match;
use App\Models\Title;
use App\Models\Roster\TagTeam;
use App\Models\Roster\Wrestler;
use App\Observers\MatchObserver;
use App\Observers\TitleObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\Roster\TagTeamObserver;
use App\Observers\Roster\WrestlerObserver;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Match::observe(MatchObserver::class);
        Title::observe(TitleObserver::class);
        Wrestler::observe(WrestlerObserver::class);
        TagTeam::observe(TagTeamObserver::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
