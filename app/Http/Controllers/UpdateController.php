<?php

namespace App\Http\Controllers;

use App\Services\GoogleCalendar;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function main(Request $request, GoogleCalendar $calendar)
    {
        $data = $request->input();
        $data['allday'] = $request->input('allday');

        $id = $data['id'];

        $oldEvent = $calendar->get($id);
        $newEvent = $calendar->createEvent($data);

        if ($newEvent->compare($oldEvent)) {
            $sharedData = array('shared_error' => true, 'shared_message' => 'Events are equal, event was not updated.');
            return redirect('/')->with($sharedData);
        }

        try {
            $calendar->update($id, $newEvent);
        } catch (\Exception $e) {

            $sharedData = array('shared_error' => true, 'shared_message' => $e->getMessage());
            return redirect('/')->with($sharedData);
        }

        $sharedData = array('shared_error' => false, 'shared_message' => "Event '" . $data['name'] . "' updated successfully.");
        return redirect('/')->with($sharedData);
    }
}
