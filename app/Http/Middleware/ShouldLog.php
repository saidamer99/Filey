<?php

namespace App\Http\Middleware;

use App\Models\Log;
use Closure;
use Illuminate\Http\Request;

class ShouldLog
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response= $next($request);
        if(app()->environment('local')){
            $log=[
                'url'=>$request->getUri(),
                'method'=>$request->getMethod(),
                'request_body'=>json_encode($request->all()),
                'response'=>$response->getContent()
            ];

            Log::create($log);
        }
        return $next($request);
    }
}
