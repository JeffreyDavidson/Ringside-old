<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Form;

class BootstrapFormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Form::component('bsText', 'components.form.text', ['name', 'value', 'attributes' => [], 'required' => null, 'label' => null, 'addon' => false, 'addonLabel' => null, 'instructions' => null]);
        Form::component('bsSelect', 'components.form.select', ['name', 'options' => [], 'selected' => null, 'attributes' => [], 'required' => null, 'label' => null, 'instructions' => null]);
        Form::component('bsDate', 'components.form.date', ['name', 'value', 'attributes' => [], 'required' => null, 'label' => null, 'instructions' => null]);
        Form::component('bsSubmit', 'components.form.submit', ['value' => 'Submit']);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
