<?php

namespace App\Http\Middleware;

use Closure;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->role_id == 1){
            return $next($request);
        }
        return response()->json([
            'status' => 'error',
            'code' => 401,
            'message' => 'You are unauthorized!',
            'data' => []
            ]);
    }
}
