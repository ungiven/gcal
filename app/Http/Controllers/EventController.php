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

        $sharedData = array('shared_error' => false, 'shared_message' => "Event '" . $data['name'] . "' created successfully.", 'added_id' => $event->id);

        return redirect('/')->with($sharedData);
    }

    // Updates calendar event
    // $id : id of event to update
    public function update(Request $request)
    {
        $this->client = $request->get('client');
        $this->calendar = new \Google_Service_Calendar($this->client);
        $calendarId = 'primary';
        $id = $request->input('id');

        /*$data = array(
            'name' => $request->input('name'),
            'date' => $request->input('date'),
            'time' => $request->input('start'),
            'end' => $request->input('end'),
            'allday' => $request->input('allday'),
        );*/

        $data = $request->get('data');

        $updatedEvent = new \Google_Service_Calendar_Event(array(
            'summary' => $data['name'],
            'start' => array(
                'date' => $data['allday'] ? date('Y-m-d', strtotime($data['date'])) : null,
                'dateTime' => $data['allday'] ? null : $request->get('start')->format(\DateTimeInterface::RFC3339),
            ),
            'end' => array(
                //'dateTime' => $start->modify('+2 hours')->format(\DateTimeInterface::RFC3339),
                'date' => $data['allday'] ? date('Y-m-d', strtotime($data['date'] . '+1 day')) : null,
                'dateTime' => $data['allday'] ? null : $request->get('end')->format(\DateTimeInterface::RFC3339),
            )
        ));

        try {
            $this->calendar->events->update($calendarId, $id, $updatedEvent, array());
        } catch (\Exception $e) {

            $sharedData = array('shared_error' => true, 'shared_message' => $e->getMessage());
            return redirect('/')->with($sharedData);
        }

        $sharedData = array('shared_error' => false, 'shared_message' => "Event '" . $data['name'] . "' updated successfully.");

        return redirect('/')->with($sharedData);
    }

    // Deletes calendar event
    // $id: id of event to delete
    public function delete(Request $request)
    {
        $this->client = $request->get('client');
        $this->calendar = new \Google_Service_Calendar($this->client);
        $id = $request->input('id');
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
