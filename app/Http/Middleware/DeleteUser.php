<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\Group;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use League\Glide\Api\Api;

class DeleteUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next): \Illuminate\Http\JsonResponse
    {
        foreach ($request->members as $member) {
           $user=User::find($member['user_id']);
           if(!$user){
               return  ApiResponse::error('User Not Found','404');
           }
           $files=$user->modifiedFiles($request->group_id)->where('status','!=','free');
           if($files->count()!=0){
               return  ApiResponse::error('User : '.$user->name.' has preserved files ','401');
           }
        }
        return $next($request);
    }
}
