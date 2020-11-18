<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *8
     * @return void
     */
    public function register()
    {   
        
        /* get the current environment mode */
        $mode = config('app.env');  

        /* get the service class based on environment mode */
        $incidentService = config("incident.{$mode}.class");

        /* 
            * bind the interface with respective service class based on environment mode ( local ,
              poduction )
        */
        $this->app->bind(
            \App\Contracts\IncidentInterface::class, // the incident interface
            $incidentService // services class based on env mode
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
