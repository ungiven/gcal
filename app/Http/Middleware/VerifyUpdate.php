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
        $id = $request->get('id');

        if (!$request->input('submit')) {
            $sharedData = array('shared_error' => true, 'shared_message' => 'Access error');
            return redirect('/')->with($sharedData);
        }

        if (!$id) {
            $sharedData = array('shared_error' => true, 'shared_message' => 'No id');
            return redirect('/')->with($sharedData);
        }

        return $next($request);
    }
}
