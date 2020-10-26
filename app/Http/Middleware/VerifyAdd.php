<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyAdd
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /*$data = $request->input();

        /dd($data);

        if (isset($data['allday']) and $data['allday'] == 'on') {
            if (!($data['name'] && $data['date'])) {
                return "bla";
            }
        } else {
            if (!($data['name'] && $data['date'] && $data['time'] && $data['end'])) {
                return "Enter event name, date and time.";
            }
        }*/

        return $next($request);
    }
}
