<?php

namespace App\Providers;

use App\Models\Match;
use App\Models\Title;
use App\Models\RosterMember;
use App\Observers\MatchObserver;
use App\Observers\TitleObserver;
use App\Observers\RosterMemberObserver;
use Illuminate\Support\ServiceProvider;

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
        RosterMember::observe(RosterMemberObserver::class);
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
