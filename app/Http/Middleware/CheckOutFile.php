<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\File;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckOutFile
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $user = checkUser();
        foreach ($request->files as $file) {
            $file = File::find($file['file_id']);
            if ($file->status != "preserved" || $file->current_editor_id != $user->id) {
                return ApiResponse::error("Cannot Checkout File", '401');
            }
        }
        return $next($request);
    }
}
