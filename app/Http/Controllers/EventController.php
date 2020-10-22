<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    private $message;
    private $client;
    private $calendar;

    public function __construct()
    {
        $this->message = array('error' => false, 'message' => null);
    }

    // Adds event to calendar
    public function add(Request $request)
    {
        $this->client = $request->get('client');
        $this->calendar = new \Google_Service_Calendar($this->client);

        # POST data
        $data = array(
            'name' => $request->input('name'),
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'end' => $request->input('end'),
            'allday' => $request->input('allday'),
        );


        /*if (!($data['name'] && $data['date'] && $data['time'] && $data['end'])) {
            return "Enter event name, date and time.";
        }*/

        # Create datetime object from POST data
        $start = new \DateTime($data['date'] . " " . $data['time'], new \DateTimeZone('Europe/Stockholm'));
        $end = new \DateTime($data['date'] . " " . $data['end'], new \DateTimeZone('Europe/Stockholm'));

        # Create calendar event object from POST data
        $calendarId = 'primary';
        $event = new \Google_Service_Calendar_Event(array(
            'summary' => $data['name'],
            'start' => array(
                // if all day event set date and not dateTime and vice versa
                'date' => $data['allday'] ? date('Y-m-d', strtotime($data['date'])) : null,
                'dateTime' => $data['allday'] ? null : $start->format(\DateTimeInterface::RFC3339),
            ),
            'end' => array(
                //'dateTime' => $start->modify('+2 hours')->format(\DateTimeInterface::RFC3339),
                'date' => $data['allday'] ? date('Y-m-d', strtotime($data['date'] . '+1 day')) : null,
                'dateTime' => $data['allday'] ? null : $end->format(\DateTimeInterface::RFC3339),
            )
        ));

        # Insert event into calendar
        try {
            $event = $this->calendar->events->insert($calendarId, $event);
        } catch (\Exception $e) {
            $sharedData = array('shared_error' => true, 'shared_message' => $e->getMessage());
            return redirect('/')->with($sharedData);
        }


        $sharedData = array('shared_error' => false, 'shared_message' => "Event '" . $data['name'] . "' created successfully.");
        return redirect('/')->with($sharedData);


        ## old message
        /*$this->message['message'] = "Event '" . $data['name'] . "' created successfully.";
        return $this->start();*/
    }

    // Updates calendar event
    // $id : id of event to update
    public function update($id = null, Request $request)
    {
        if (!$id) {
            $sharedData = array('shared_error' => true, 'shared_message' => 'No id given');
            return redirect('/')->with($sharedData);
        }

        if (!$request->input('submit')) {
            $sharedData = array('shared_error' => true, 'shared_message' => 'Access error');
            return redirect('/')->with($sharedData);
        }

        $this->client = $request->get('client');
        $this->calendar = new \Google_Service_Calendar($this->client);

        $calendarId = 'primary';
        if ($request->input('submit') !== null) {

            $data = array(
                'name' => $request->input('name'),
                'date' => $request->input('date'),
                'time' => $request->input('start'),
                'end' => $request->input('end'),
                'allday' => $request->input('allday'),
            );


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

            $pStartFormat = new \DateTime($data['date'] . " " . $data['time'] . '+02:00', new \DateTimeZone('Europe/Stockholm'));
            $pEndFormat = new \DateTime($data['date'] . " " . $data['end'] . '+02:00', new \DateTimeZone('Europe/Stockholm'));


            // All day event
            /*var_dump(!empty($data['allday']));
            var_dump($eventAllDay);
            dd();*/

            # Check if events are identical
            // All day event
            if (
                !empty($data['allday']) &&
                $eventAllDay &&
                $eventName == $data['name']
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
            } else {
                $updatedEvent = new \Google_Service_Calendar_Event(array(
                    'summary' => $data['name'],
                    'start' => array(
                        'date' => $data['allday'] ? date('Y-m-d', strtotime($data['date'])) : null,
                        'dateTime' => $data['allday'] ? null : $pStartFormat->format(\DateTimeInterface::RFC3339),
                    ),
                    'end' => array(
                        //'dateTime' => $start->modify('+2 hours')->format(\DateTimeInterface::RFC3339),
                        'date' => $data['allday'] ? date('Y-m-d', strtotime($data['date'] . '+1 day')) : null,
                        'dateTime' => $data['allday'] ? null : $pEndFormat->format(\DateTimeInterface::RFC3339),
                    )
                ));

                try {
                    $this->calendar->events->update($calendarId, $id, $updatedEvent, array());
                } catch (\Exception $e) {

                    $sharedData = array('shared_error' => true, 'shared_message' => $e->getMessage());
                    return redirect('/')->with($sharedData);
                }

                $sharedData = array('shared_error' => false, 'shared_message' => "Event '" . $eventName . "' updated successfully.");
                return redirect('/')->with($sharedData);
            }
        }
    }

    // Deletes calendar event
    // $id: id of event to delete
    public function delete($id = null, Request $request)
    {
        // Error if no delete post
        if (!$id) {
            $sharedData = array('shared_error' => true, 'shared_message' => 'No id');
            return redirect('/')->with($sharedData);
        }

        if (!$request->input('delete')) {

            $sharedData = array('shared_error' => true, 'shared_message' => 'Access error');
            return redirect('/')->with($sharedData);
        }

        $this->client = $request->get('client');
        $this->calendar = new \Google_Service_Calendar($this->client);
        $calendarId = 'primary';

        $event = $this->calendar->events->get($calendarId, $id, array());

        try {
            $this->calendar->events->delete($calendarId, $id, array());
        } catch (\Exception $e) {

            $sharedData = array('shared_error' => true, 'shared_message' => $e->getMessage());
            return redirect('/')->with($sharedData);
        }

        $sharedData = array('shared_error' => false, 'shared_message' => "Deleted event '" . $event->getSummary() . "'.");
        return redirect('/')->with($sharedData);
    }
}
