<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\File;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckFile
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next):JsonResponse
    {
        foreach ($request->files_ids as $file_id) {
            $file=File::find($file_id);
            if($file->status!="free"){
                return ApiResponse::error("File is Preserved",'401');
            }
        }
        return $next($request);
    }
}
