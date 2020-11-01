<?php

namespace App\Services;

class GoogleCalendarEvent extends \Google_Service_Calendar_Event
{
    public function compare($event)
    {
        return ($this->summary == $event->summary &&
            $this->start->date == $event->start->date &&
            $this->start->dateTime == $event->start->dateTime &&
            $this->end->date == $event->end->date &&
            $this->end->dateTime == $event->end->dateTime);
    }
}
