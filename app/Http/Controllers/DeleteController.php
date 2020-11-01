<?php

namespace App\Http\Controllers;

use App\Services\GoogleCalendar;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function main(Request $request, GoogleCalendar $calendar)
    {
        $id = $request->input('id');

        $event = $calendar->get($id);

        try {
            $result = $calendar->delete($id);
        } catch (\Exception $e) {

            $sharedData = array('shared_error' => true, 'shared_message' => $e->getMessage());
            return redirect('/')->with($sharedData);
        }

        $sharedData = array('shared_error' => false, 'shared_message' => "Deleted event '" . $event->getSummary() . "'.");
        return redirect('/')->with($sharedData);
    }
}
