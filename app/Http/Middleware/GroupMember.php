<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\Group;
use Closure;
use Illuminate\Http\Request;

class GroupMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user=checkUser();
        $group=Group::find($request->group_id);
        if(!$group){
            return  ApiResponse::error('Group Not Found',404);
        }
        $userInGroup=$group->members->where('id',$user->id);
        if(!$userInGroup)
        {
            return  ApiResponse::error('user in Not Group Member',404);
        }
        return $next($request);
    }
}
