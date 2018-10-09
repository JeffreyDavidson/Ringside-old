<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            'titles.partials.records',
            'App\Http\ViewComposers\TitleRecordsViewComposer'
        );

        View::composer(
            'matches.partials.form',
            'App\Http\ViewComposers\MatchFormViewComposer'
        );

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
