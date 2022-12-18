<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\File;
use App\Models\Group;
use Closure;
use Illuminate\Http\Request;

class FileOwner
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
        foreach ($request->files_ids as $file_id){
           $file= File::find($file_id);
           if(!$file){
               return ApiResponse::error('file Not Found ','404');
           }
           if($file->owner_id!=$user->id){
               return ApiResponse::error('User is not file owner','401');
           }
        }
        return $next($request);
    }
}
