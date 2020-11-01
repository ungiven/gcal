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

            $tokenPath = base_path() . '/token.json';
            if (file_exists($tokenPath)) {
                $accessToken = json_decode(file_get_contents($tokenPath), true);
                $client->setAccessToken($accessToken);
            } else {
                $authUrl = $client->createAuthUrl();
                return redirect('/auth')->with('authUrl', $authUrl);
            }

            if ($client->isAccessTokenExpired()) {
                // Refresh the token if possible, else fetch a new one.
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                } else {
                    $authUrl = $client->createAuthUrl();
                    return redirect('/auth')->with('authUrl', $authUrl);
                }

                if (!file_exists(dirname($tokenPath))) {
                    mkdir(dirname($tokenPath), 0700, true);
                }
                file_put_contents($tokenPath, json_encode($client->getAccessToken()));
            }
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
