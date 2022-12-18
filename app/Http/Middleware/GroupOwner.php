<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\Group;
use Closure;
use Illuminate\Http\Request;

class GroupOwner
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
        if($group->owner->id!=$user->id)
        {
            return ApiResponse::error('User is not group owner','401');
        }
        return $next($request);
    }
}
