<?php

namespace App\Providers;

use Form;
use Illuminate\Support\ServiceProvider;

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
        Form::component('bsTextarea', 'components.form.textarea', ['name', 'value', 'attributes' => [], 'required' => null, 'label' => null, 'addon' => false, 'addonLabel' => null, 'instructions' => null]);
        Form::component('bsSelect', 'components.form.select', ['name', 'options' => [], 'selected' => null, 'attributes' => [], 'required' => null, 'label' => null, 'instructions' => null, 'multiple' => false]);
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
