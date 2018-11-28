<?php

namespace App\Providers;

use App\Models\Match;
use App\Models\Title;
use App\Models\Wrestler;
use App\Observers\MatchObserver;
use App\Observers\TitleObserver;
use App\Observers\WrestlerObserver;
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
        Wrestler::observe(WrestlerObserver::class);
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
