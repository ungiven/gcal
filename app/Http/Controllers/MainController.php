<?php

namespace App\Http\Controllers;

//vendor\google\apiclient\src\Google;

use Illuminate\Http\Request;



class MainController extends Controller
{
    private $calendar;
    private $client;
    private $message;

    public function __construct(Request $request)
    {
        $this->message = ['error' => false, 'message' => NULL];
    }


    public function start(Request $request)
    {
        //$this->calendar = new \Google_Service_Calendar($this->client);
        $this->client = $request->get('client');
        $this->calendar = new \Google_Service_Calendar($this->client);




        $calendarId = 'primary';
        $optParams = array(
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
            'timeMax' => date('c', time() + (7 * 24 * 60 * 60)),
        );
        $results = $this->calendar->events->listEvents($calendarId, $optParams);

        $events = $results->getItems();

        # Create events json object for blade / vue / view and and pass it
        $this->message['title'] = 'a';
        $this->message['items'] = [];
        foreach ($events as $event) {
            /*print('<pre>');
            var_dump($event);
            print('</pre>');*/
            $start = $event->start->dateTime;
            $end = $event->end->dateTime;

            array_push(
                $this->message['items'],
                [
                    'name' => $event->getSummary(),
                    'start' => empty($start) ? $event->start->date : $start,
                    //'id' => $event->getId(),
                    'id' => $event->id,
                    'end' => empty($end) ? $event->end->date : $end,
                    'htmlLink' => $event->htmlLink,
                    //'date' => $event->start->date,
                    //'dateTime' => $event->start->datetime,
                ]
            );
        }

        $this->message['items'] = json_encode($this->message['items']);

        return view('start')->with($this->message);
    }



    public function auth(Request $request)
    {
        //$this->calendar = new \Google_Service_Calendar($this->client);
        $client = new \Google_Client();
        $client->setApplicationName('Google Calendar API PHP');
        $client->setScopes(\Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig(base_path() . '/credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $this->client = $client;

        if (!empty($request->input('authCode'))) {
            $authCode = $request->input('authCode');
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
        } else {

            $authUrl = $this->client->createAuthUrl();
            return view('auth')->with('authUrl', $authUrl);
        }
    }
}
