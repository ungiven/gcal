<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyUpdate
{
    private $calendar;
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
        $id = $request->input('id');


        /*if (!$request->input('submit')) {
            $sharedData = array('shared_error' => true, 'shared_message' => 'Access error');
            return redirect('/')->with($sharedData);
        }*/

        if (!$id) {
            $sharedData = array('shared_error' => true, 'shared_message' => 'No id');
            return redirect('/')->with($sharedData);
        }

        $this->client = $request->get('client');
        $this->calendar = new \Google_Service_Calendar($this->client);
        $calendarId = 'primary';



        $data = array(
            'name' => $request->input('name'),
            'date' => $request->input('date'),
            'time' => $request->input('start'),
            'end' => $request->input('end'),
            'allday' => $request->input('allday'),
        );

        //dd($request->input());


        // Get event from calendar or error
        try {
            $event = $this->calendar->events->get($calendarId, $id, array());
        } catch (\Exception $e) {
            $sharedData = array('shared_error' => true, 'shared_message' => $e->getMessage());
            return redirect('/')->with($sharedData);
        }


        // Calculate event information
        $start = $event->start->dateTime;
        $end = $event->end->dateTime;
        $eventAllDay = empty($start);

        $eventName = $event->getSummary();
        $eventStart = empty($start) ? $event->start->date : $start;
        $eventEnd = empty($end) ? $event->end->date : $end;

        date_default_timezone_set('Europe/Stockholm');
        $timeOffset = date('P');

        $pStartFormat = new \DateTime($data['date'] . " " . $data['time'] . $timeOffset, new \DateTimeZone('Europe/Stockholm'));
        $pEndFormat = new \DateTime($data['date'] . " " . $data['end'] . $timeOffset, new \DateTimeZone('Europe/Stockholm'));




        # Check if events are identical

        if (
            !empty($data['allday']) &&
            $eventAllDay &&
            $eventName == $data['name'] &&
            $eventStart == $data['date']
        ) {
            $sharedData = array('shared_error' => true, 'shared_message' => "Identical post, '" . $eventName . "' was not updated.");
            return redirect('/')->with($sharedData);
            // Regular event
        } else if (
            $eventStart == $pStartFormat->format(\DateTimeInterface::RFC3339) &&
            $eventEnd == $pEndFormat->format(\DateTimeInterface::RFC3339) &&
            $eventName == $data['name'] &&
            $eventAllDay == !empty($data['allday'])

            #if identical event -> error
        ) {
            $sharedData = array('shared_error' => true, 'shared_message' => "Identical post, '" . $eventName . "' was not updated.");
            return redirect('/')->with($sharedData);

            #otherwise add the event and return
        }

        $request->attributes->add(['data' => $data, 'start' => $pStartFormat, 'end' => $pEndFormat]);

        return $next($request);
    }
}
