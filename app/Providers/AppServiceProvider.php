<?php

namespace App\Providers;

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
        $this->app->singleton(\Google_Client::class, function ($app) {
            $client = new \Google_Client();

            $client->setApplicationName('Google Calendar API PHP');
            $client->setScopes(\Google_Service_Calendar::CALENDAR);
            $client->setAuthConfig(base_path() . '/credentials.json');
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            return $client;
        });
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
