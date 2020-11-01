<?php

namespace App\Http\Controllers;

use Google_Client;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $client;

    public function __construct(Google_Client $client)
    {
        $this->client = $client;
    }

    public function index()
    {
        $authUrl = $this->client->createAuthUrl();
        return view('auth')->with('authUrl', $authUrl);
    }

    public function auth(Request $request)
    {
        $request->validate([
            'authkey' => 'required',
        ]);

        $authCode = $request->input('authkey');
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);

        $this->client->setAccessToken($accessToken);


        $tokenPath = base_path() . '/token.json';

        if (array_key_exists('error', $accessToken)) {
            throw new \Exception(join(', ', $accessToken));
        }

        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));

        return redirect('/');
    }
}
