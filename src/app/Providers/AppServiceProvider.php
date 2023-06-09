<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::registerNavigationGroups([
            'Training Resources',
            'Trainings',
            'Access Controls'
        ]);
        Filament::serving(function () {
            Filament::registerViteTheme('resources/css/filament.css');
        });
    }
}
