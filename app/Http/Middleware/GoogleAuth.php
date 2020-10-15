<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GoogleAuth
{
    private $client;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $client = new \Google_Client();
        $client->setApplicationName('Google Calendar API PHP');
        $client->setScopes(\Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig(base_path() . '/credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $this->client = $client;

        $tokenPath = base_path() . '/token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($accessToken);
        } else {
            $authUrl = $this->client->createAuthUrl();
            #print('A');
            return redirect('/auth')->with('authUrl', $authUrl);
        }

        if ($this->client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($this->client->getRefreshToken()) {
                $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
            } else {
                $authUrl = $this->client->createAuthUrl();
                #print('B');
                return redirect('/auth')->with('authUrl', $authUrl);
            }
        }


        return $next($request);
    }
}
