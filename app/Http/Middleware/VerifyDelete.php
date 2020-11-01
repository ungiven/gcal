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
        try {
            $data = $request->validate([
                'id' => 'required',
            ]);
        } catch (\Exception $e) {
            //dd($e);
            #$sharedData = array('shared_error' => true, 'shared_message' => 'No id');
            #return redirect('/')->with($sharedData);
        }

        return $next($request);
    }
}
