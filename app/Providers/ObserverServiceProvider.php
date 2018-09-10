<?php

namespace App\Providers;

use App\Models\Match;
use App\Models\Title;
use App\Observers\MatchObserver;
use App\Observers\TitleObserver;
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
