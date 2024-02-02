<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class APIAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token= '9801c30ca14c57ff49dfa936fd1c3f2f';
        $requestedToken=$request->api_token;
        //dd($requestedToken);
        if (empty($requestedToken)) {
            return response()->json(['status' => 'API Token is Required!!', 'status_code' => 422], 422);
        }
        if ($requestedToken != $token) {
            return response()->json(['status' => 'API Token is Invalid!!', 'status_code' => 422], 422);
        }
        return $next($request);
    }
}
