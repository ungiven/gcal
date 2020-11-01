<?php

namespace App\Services;

class GoogleCalendar
{

    public $calendar;
    private $calendarId;

    public function __construct(\Google_Service_Calendar $calendar)
    {
        $this->calendarId = 'primary';
        $this->calendar = $calendar;
    }

    public function get($id)
    {
        return $this->calendar->events->get($this->calendarId, $id);
    }

    public function listEvents($optParams)
    {
        $results = $this->calendar->events->listEvents($this->calendarId, $optParams);
        return $results->getItems();
    }

    public function insert($event)
    {
        $result = $this->calendar->events->insert($this->calendarId, $event);
        return $result;
    }

    public function delete($id)
    {
        $result = $this->calendar->events->delete($this->calendarId, $id);
        return $result;
    }

    public function update($id, $event)
    {
        return $this->calendar->events->update($this->calendarId, $id, $event);
    }

    /**
     * Creates an event and returns it.
     * @param array $data {
     *
     *     @type string name Event name
     *     @type string date Date of event, YYYY-MM-DD
     *     @type string start Event start time
     *     @type string end Event end time
     * }
     * @return GoogleCalendarEvent
     */

    public function createEvent($data)
    {
        if ($data['allday']) {
            $data['start'] = '00:00';
            $data['end'] = '00:00';
        }

        date_default_timezone_set('Europe/Stockholm');
        $start = date('c', strtotime($data['date'] . " " . $data['start']));
        $end = date('c', strtotime($data['date'] . " " . $data['end']));

        $eventData = array(
            'summary' => $data['name'],
            'start' => array(
                // if all day event set date and not dateTime and vice versa
                'date' => $data['allday'] ? date('Y-m-d', strtotime($data['date'])) : null,
                'dateTime' => $data['allday'] ? null : $start,
            ),
            'end' => array(
                'date' => $data['allday'] ? date('Y-m-d', strtotime($data['date'] . '+1 day')) : null,
                'dateTime' => $data['allday'] ? null : $end,
            )
        );

        return new GoogleCalendarEvent($eventData);
    }

    /**
     * Sets a new calendar id.
     * @param string $id New id.
     * @return void
     */

    public function setCalendarId($id)
    {
        $this->calendarId = $id;
    }
}
