<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {

        return view('test')->with('hej', 'HEJ');
    }

    public function best()
    {
        $sharedData = array('shared_error' => true, 'shared_message' => 'This is the shared message');
        //view()->share($sharedData);

        //return view('test', $sharedData);
        return redirect('/test')->with($sharedData);
    }
}
