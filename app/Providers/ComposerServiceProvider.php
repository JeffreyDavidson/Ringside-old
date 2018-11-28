<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\MatchFormViewComposer;
use App\Http\ViewComposers\TitleRecordsViewComposer;

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
            TitleRecordsViewComposer::class
        );

        View::composer(
            'matches.partials.form',
            MatchFormViewComposer::class
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
