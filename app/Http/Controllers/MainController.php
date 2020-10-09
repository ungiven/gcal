<?php

namespace App\Http\Controllers;

//vendor\google\apiclient\src\Google;

use Illuminate\Http\Request;


//require base_path() . '/vendor/autoload.php';


//* Returns an authorized API client.
//* return Google_Client the authorized client object

/*function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Calendar API PHP Quickstart');
    $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
    $client->setAuthConfig(base_path() . '/credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = base_path() . '/token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("%s", $authUrl);
            //print 'Enter verification code: ';
            //$authCode = trim(fgets(STDIN));

            $authCode = "4/4wFrJqmnopd-E1alaB7autLJbE8ItEJBBe9bfLvCHaipZ7L3CZ1vwJk";

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}


// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Calendar($client);

// Print the next 10 events on the user's calendar.
$calendarId = 'primary';
$optParams = array(
    'maxResults' => 10,
    'orderBy' => 'startTime',
    'singleEvents' => true,
    'timeMin' => date('c'),
);
$results = $service->events->listEvents($calendarId, $optParams);
$events = $results->getItems();

if (empty($events)) {
    print "No upcoming events found.\n";
} else {
    print "Upcoming events:\n";
    foreach ($events as $event) {
        $start = $event->start->dateTime;
        if (empty($start)) {
            $start = $event->start->date;
        }
        printf("%s (%s)\n", $event->getSummary(), $start);
    }
}*/

class MainController extends Controller
{
    private $calendar;
    private $client;

    public function __construct()
    {
        $client = new \Google_Client();
        $client->setApplicationName('Google Calendar API PHP');
        $client->setScopes(\Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig(base_path() . '/credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $this->client = $client;

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = base_path() . '/token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($this->client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($this->client->getRefreshToken()) {
                $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $this->client->createAuthUrl();
                //printf("%s", $authUrl);
                //print 'Enter verification code: ';
                //$authCode = trim(fgets(STDIN));

                //print($authUrl);



                //return redirect()->view('auth');


                $authCode = "4/5AH-EDK4QbmqAfmr3FohQCut2ENxkSMxT8XtRTsU-CZivXRtPkgH5JE";

                // Exchange authorization code for an access token.
                $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);
                $this->client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new \Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        $this->calendar = new \Google_Service_Calendar($this->client);
    }


    public function start()
    {
        $calendarId = 'primary';
        $optParams = array(
            'maxResults' => 5,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        );
        $results = $this->calendar->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        /*if (empty($events)) {
            print "No upcoming events found.\n";
        } else {
            print "Upcoming events:\n";
            foreach ($events as $event) {
                $start = $event->start->dateTime;
                if (empty($start)) {
                    $start = $event->start->date;
                }
                printf("%s (%s)\n", $event->getSummary(), $start);
            }
        }*/

        $data = [];
        $data['title'] = 'phpTitle';
        $data['items'] = [];
        foreach ($events as $event) {
            $start = $event->start->dateTime;

            array_push(
                $data['items'],
                [
                    'name' => $event->getSummary(),
                    'start' => empty($start) ? $event->start->date : $start,
                    //'id' => $event->getId(),
                    'id' => $event->id,
                    //'date' => $event->start->date,
                    //'dateTime' => $event->start->datetime,
                ]
            );
        }

        $data['items'] = json_encode($data['items']);


        //return var_dump($events[0]);

        /*$data = [
            'title' => 'php Title',
            'items' => json_encode([
                [
                    'name' => 'roligt event',
                    'start' => '10.00',
                    'end' => '11.00',
                    'id' => 0
                ],
                [
                    'name' => 'roligt event',
                    'start' => '10.00',
                    'end' => '11.00',
                    'id' => 1
                ],
                [
                    'name' => 'roligt event',
                    'start' => '10.00',
                    'end' => '11.00',
                    'id' => 2
                ]
            ]),

        ];*/
        var_dump($events[0]);
        //var_dump(get_class_methods($this->calendar->events));
        //print($this->calendar->events->get($calendarId,));
        return view('start')->with($data);
    }

    public function add(Request $request)
    {
        # POST data
        $data = array(
            'name' => $request->input('name'),
            'date' => $request->input('date'),
            'time' => $request->input('time'),
        );

        # Create datetime object fro POST data
        $start = new \DateTime($data['date'] . " " . $data['time'], new \DateTimeZone('Europe/Stockholm'));

        # Create calendar event object from POST data
        $calendarId = 'primary';
        $event = new \Google_Service_Calendar_Event(array(
            'summary' => $data['name'],
            'start' => array(
                'dateTime' => $start->format(\DateTimeInterface::RFC3339),
            ),
            'end' => array(
                'dateTime' => $start->modify('+2 hours')->format(\DateTimeInterface::RFC3339),
            )
        ));

        # Insert event into calendar
        $event = $this->calendar->events->insert($calendarId, $event);
        printf('Event created: %s\n', $event->htmlLink);


        return 'Add to IT';



        //$dateTime->setTimezone(new \DateTimeZone('Europe/Stockholm'));
        //var_dump($dateTime);



        var_dump($data);
    }

    public function update($id)
    {
        return 'Updated id: ' . $id;
    }

    public function auth($id = Null)
    {
        if ($id) {
            return 'Auth id: ' . $id;
        } else {
            $data = [];
            $data['authUrl'] = $this->client->createAuthUrl();
            return view('auth')->with($data);
        }
    }

    public function item($id = Null)
    {
    }
}
