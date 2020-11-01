<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyUpdate
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
        try {
            $data = $request->validate(array(
                'id' => 'required',
                'name' => 'required|string',
                'date' => 'required|date',
                'start' => 'required_without:allday',
                'end' => 'required_without:allday',
            ));
        } catch (\Exception $e) {
            //dd($e);
        }

        $request->input('end')->set('nej');

        return $next($request);
    }
}
