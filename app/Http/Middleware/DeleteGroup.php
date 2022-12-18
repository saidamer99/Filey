<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\Group;
use Closure;
use Illuminate\Http\Request;

class DeleteGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next): \Illuminate\Http\JsonResponse
    {
        $group=Group::find($request->group_id);
        if(!$group){
            return ApiResponse::error('Group Not found');
        }
        $files=$group->groupFiles->where('status','!=','free');
        if($files->count()!=0){
            return ApiResponse::error('Group contains preserved files');
        }
        return $next($request);
    }
}
