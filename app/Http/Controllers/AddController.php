<?php

namespace App\Http\Controllers;

use App\Services\GoogleCalendar;
use Illuminate\Http\Request;

class AddController extends Controller
{
    public function main(Request $request, GoogleCalendar $calendar)
    {
        $data = $request->input();
        $data['allday'] = $request->input('allday');

        $event = $calendar->createEvent($data);

        try {
            $result = $calendar->insert($event);
        } catch (\Exception $e) {
            $sharedData = array('shared_error' => true, 'shared_message' => $e->getMessage());
            return redirect('/')->with($sharedData);
        }

        # redirect to start page on success
        $sharedData = array('shared_error' => false, 'shared_message' => "Event '" . $data['name'] . "' created successfully.", 'added_id' => $result->id);
        return redirect('/')->with($sharedData);
    }
}
