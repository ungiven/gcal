<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\GoogleCalendar;

class MainController extends Controller
{
    private $client;
    private $message;

    public function __construct()
    {
        $this->message = ['error' => false, 'message' => NULL];
    }

    public function index(GoogleCalendar $calendar)
    {
        $optParams = array(
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
            'timeMax' => date('c', time() + (7 * 24 * 60 * 60)),
        );

        $events = $calendar->listEvents($optParams);

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
                    'id' => $event->id,
                    'end' => empty($end) ? $event->end->date : $end,
                    'htmlLink' => $event->htmlLink,
                ]
            );
        }

        //$this->message['items'] = json_encode($this->message['items']);

        return view('start')->with($this->message);
    }
}
