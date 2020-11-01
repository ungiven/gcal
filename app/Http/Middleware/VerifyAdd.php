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
        try {
            $request->validate([
                'name' => 'required|string',
                'date' => 'required|date',
                'start' => 'required_without:allday',
                'end' => 'required_without:allday',
                'allday' => 'nullable'
            ]);
        } catch (\Exception $e) {
            dd($e);
        }

        return $next($request);
    }
}
