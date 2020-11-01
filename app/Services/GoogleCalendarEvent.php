<?php

namespace App\Services;

class GoogleCalendarEvent extends \Google_Service_Calendar_Event
{
    /**
     * Compares this event with event.
     * @param Google_Service_Calendar_Event $event
     * @return bool True if events are equal else false.
     */

    public function compare($event)
    {
        return ($this->summary == $event->summary &&
            $this->start->date == $event->start->date &&
            $this->start->dateTime == $event->start->dateTime &&
            $this->end->date == $event->end->date &&
            $this->end->dateTime == $event->end->dateTime);
    }
}
