<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddDeleteFileRequest;
use App\Http\Requests\AddDeleteUserRequest;
use App\Http\Requests\AddFileRequest;
use App\Http\Requests\DeleteFileRequest;
use App\Http\Requests\GroupDetailsRequest;
use App\Models\User;
use App\Services\Group\GroupServiceImplement;
use Illuminate\Http\Request;
use PHPUnit\Exception;


class GroupController extends Controller
{

    public User $user;

    protected GroupServiceImplement $mainService;

    public function __construct(GroupServiceImplement $mainService)
    {
        $this->mainService = $mainService;
    }

    public function owned(): \Illuminate\Http\JsonResponse
    {
        return $this->mainService->ownedHandler();
    }

    public function belongs(): \Illuminate\Http\JsonResponse
    {
        return $this->mainService->belongsHandler();

    }

    public function details(GroupDetailsRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->mainService->detailsHandler($request);
    }

    public function users(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->mainService->usersHandler($request);
    }


    public function addUsers(AddDeleteUserRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->mainService->addUsersHandler($request);

        }catch (Exception $exception){
            return ApiResponse::error($exception->getMessage());
        }
    }

    public function deleteUsers(AddDeleteUserRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->mainService->deleteUsersHandler($request);

    }

    public function addFiles(AddFileRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->mainService->addFilesHandler($request);
    }

    public function deleteFiles(DeleteFileRequest $request): \Illuminate\Http\JsonResponse
    {

        return $this->mainService->deleteFilesHandler($request);

    }


}
