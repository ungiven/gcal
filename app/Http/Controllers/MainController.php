<?php

namespace App\Http\Controllers;

//vendor\google\apiclient\src\Google;

use Illuminate\Http\Request;



class MainController extends Controller
{
    private $calendar;
    private $client;
    private $message;

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
                $authUrl = $this->client->createAuthUrl();
                return view('/auth')->with('authUrl', $authUrl);
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        $this->message = ['error' => false, 'message' => NULL];

        $this->calendar = new \Google_Service_Calendar($this->client);
    }


    public function start()
    {
        //$this->calendar = new \Google_Service_Calendar($this->client);
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
                    //'date' => $event->start->date,
                    //'dateTime' => $event->start->datetime,
                ]
            );
        }

        $this->message['items'] = json_encode($this->message['items']);


        return view('start')->with($this->message);
    }

    public function add(Request $request)
    {
        # POST data
        $data = array(
            'name' => $request->input('name'),
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'end' => $request->input('end'),
        );

        if (!($data['name'] && $data['date'] && $data['time'] && $data['end'])) {
            return "Enter event name, date and time.";
        }

        # Create datetime object fro POST data
        $start = new \DateTime($data['date'] . " " . $data['time'], new \DateTimeZone('Europe/Stockholm'));
        $end = new \DateTime($data['date'] . " " . $data['end'], new \DateTimeZone('Europe/Stockholm'));

        # Create calendar event object from POST data
        $calendarId = 'primary';
        $event = new \Google_Service_Calendar_Event(array(
            'summary' => $data['name'],
            'start' => array(
                'dateTime' => $start->format(\DateTimeInterface::RFC3339),
            ),
            'end' => array(
                //'dateTime' => $start->modify('+2 hours')->format(\DateTimeInterface::RFC3339),
                'dateTime' => $end->format(\DateTimeInterface::RFC3339),
            )
        ));

        # Insert event into calendar
        try {
            $event = $this->calendar->events->insert($calendarId, $event);
        } catch (\Exception $e) {
            $sharedData = array('shared_error' => true, 'shared_message' => $e->getMessage());
            return redirect('/')->with($sharedData);

            ## old message
            /*$this->message['message'] = $e->getMessage();
            $this->message['error'] = true;
            return $this->start();*/
        }

        $sharedData = array('shared_error' => false, 'shared_message' => "Event '" . $data['name'] . "' created successfully.");
        return redirect('/')->with($sharedData);

        ## old message
        /*$this->message['message'] = "Event '" . $data['name'] . "' created successfully.";
        return $this->start();*/
    }

    public function update($id, Request $request)
    {
        if ($id) {
            $calendarId = 'primary';
            if ($request->input('submit') !== null) {


                $optParams = array(
                    'id' => $id,
                );



                $pName = $request->input('name');
                $pDate = $request->input('date');
                $pStart = $request->input('start');
                $pEnd = $request->input('end');

                $event = $this->calendar->events->get($calendarId, $id, array());
                $start = $event->start->dateTime;
                $end = $event->end->dateTime;

                $eventName = $event->getSummary();
                $eventStart = empty($start) ? $event->start->date : $start;
                $eventEnd = empty($end) ? $event->end->date : $end;

                /*print($eventStart);
                print('<br>');*/

                $pStartFormat = new \DateTime($pDate . " " . $pStart . '+02:00', new \DateTimeZone('Europe/Stockholm'));
                $pEndFormat = new \DateTime($pDate . " " . $pEnd . '+02:00', new \DateTimeZone('Europe/Stockholm'));


                # Check if events are identical
                if (
                    $eventStart == $pStartFormat->format(\DateTimeInterface::RFC3339) &&
                    $eventEnd == $pEndFormat->format(\DateTimeInterface::RFC3339) &&
                    $eventName == $pName

                    #if identical event -> error
                ) {
                    $sharedData = array('shared_error' => true, 'shared_message' => "Identical post, '" . $eventName . "' was not updated.");
                    return redirect('/')->with($sharedData);

                    ## old message
                    /*$this->message['error'] = true;
                    $this->message['message'] = "Identical post, '" . $eventName . "' was not updated.";
                    return $this->start();*/
                    //print('identical event');

                    #otherwise add the event and return
                } else {
                    $updatedEvent = new \Google_Service_Calendar_Event(array(
                        'summary' => $pName,
                        'start' => array(
                            'dateTime' => $pStartFormat->format(\DateTimeInterface::RFC3339),
                        ),
                        'end' => array(
                            //'dateTime' => $start->modify('+2 hours')->format(\DateTimeInterface::RFC3339),
                            'dateTime' => $pEndFormat->format(\DateTimeInterface::RFC3339),
                        )
                    ));

                    try {
                        $this->calendar->events->update($calendarId, $id, $updatedEvent, array());
                    } catch (\Exception $e) {

                        $sharedData = array('shared_error' => true, 'shared_message' => $e->getMessage());
                        return redirect('/')->with($sharedData);

                        ## old message
                        /*$this->message['error'] = true;
                        $this->message['message'] = $e->getMessage();
                        return $this->start();*/
                    }

                    $sharedData = array('shared_error' => false, 'shared_message' => "Event '" . $eventName . "' updated successfully.");
                    return redirect('/')->with($sharedData);

                    ## old message
                    /*$this->message['message'] = "Event '" . $eventName . "' updated successfully.";
                    return $this->start();*/
                }
                #
                # delete event
                #
            } else if ($request->input('delete') !== null) {
                $event = $this->calendar->events->get($calendarId, $id, array());

                try {
                    $this->calendar->events->delete($calendarId, $id, array());
                } catch (\Exception $e) {
                    # old message
                    /*$this->message['message'] = $e->getMessage();
                    $this->error = true;*/
                    $sharedData = array('shared_error' => true, 'shared_message' => $e->getMessage());
                    //return $this->start();
                    return redirect('/')->with($sharedData);
                }

                ## old message
                /*$this->message['message'] = "Deleted event '" . $event->getSummary() . "'.";
                return $this->start();*/

                $sharedData = array('shared_error' => false, 'shared_message' => "Deleted event '" . $event->getSummary() . "'.");
                return redirect('/')->with($sharedData);
            }
        } else {
            return 'No id';
        }
    }

    public function auth(Request $request)
    {

        if (!empty($request->input('authCode'))) {
            $authCode = $request->input('authCode');
            print $authCode;
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
        } else {

            $authUrl = $this->client->createAuthUrl();
            return view('auth')->with('authUrl', $authUrl);
        }
    }
}
