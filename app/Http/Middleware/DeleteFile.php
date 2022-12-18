<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\File;
use Closure;
use Illuminate\Http\Request;

class DeleteFile
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user=checkUser();
        foreach ($request->files  as $file){
            $file=File::find($file['file-id']);
            if($file->status!='free'){
                return  ApiResponse::error('File is preserved',"401");
            }
        }
        return $next($request);
    }
}
