<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyDelete
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

        if (!$id) {
            $sharedData = array('shared_error' => true, 'shared_message' => 'No id');
            return redirect('/')->with($sharedData);
        }

        if (!$request->input('delete')) {

            $sharedData = array('shared_error' => true, 'shared_message' => 'Access error');
            return redirect('/')->with($sharedData);
        }

        return $next($request);
    }
}
