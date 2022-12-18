<?php

namespace App\Services\Group;

use App\Helpers\ApiResponse;
use App\Http\Resources\FileResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\MemberResource;
use App\Http\Resources\PaginationResource;
use LaravelEasyRepository\Service;
use App\Repositories\Group\GroupRepository;

class GroupServiceImplement extends Service implements GroupService
{

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected $mainRepository;

    public function __construct(GroupRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function homeHandler(): \Illuminate\Http\JsonResponse
    {
        $data=$this->mainRepository->home();
        if($data['status'])
        {
            return ApiResponse::success([
                'groups' => GroupResource::collection($data['groups']),
                'public-group-files' => FileResource::collection($data['public-group-files'])
            ], '', $data['code'], new PaginationResource($data['groups']));
        }
        return ApiResponse::error($data['message'],$data['code']);

    }

    public function ownedHandler(): \Illuminate\Http\JsonResponse
    {
        $data = $this->mainRepository->owned();
        if ($data['status']) {
            return ApiResponse::success([
                'groups' => GroupResource::collection($data['groups']),
            ], '', $data['code'], new PaginationResource($data['groups']));

        } else {
            return ApiResponse::error($data['message'], $data['code']);
        }
    }

    public function belongsHandler(): \Illuminate\Http\JsonResponse
    {
        $data = $this->mainRepository->belongs();
        if ($data['status']) {
            return ApiResponse::success([
                'groups' => GroupResource::collection($data['groups']),
            ], '', $data['code'], new PaginationResource($data['groups']));

        } else {
            return ApiResponse::error($data['message'], $data['code']);
        }
    }

    public function detailsHandler($request): \Illuminate\Http\JsonResponse
    {
        $data = $this->mainRepository->details($request);
        if ($data['status']) {
            return ApiResponse::success([
                'group-members' => MemberResource::collection($data['group-members']),
                'group-files' => FileResource::collection($data['group-files']->all()),
            ], '', '200', new PaginationResource($data['group-files']));
        } else {
            return ApiResponse::error($data['message'], $data['code']);
        }

    }

    public function usersHandler($request): \Illuminate\Http\JsonResponse
    {
        $data = $this->mainRepository->users($request);
        if ($data['status']) {
            return ApiResponse::success(MemberResource::collection($data['users']));
        }
        return ApiResponse::error($data['message'], $data['code']);
    }

    public function addUsersHandler($request): \Illuminate\Http\JsonResponse
    {
        $data = $this->mainRepository->addUsers($request);
        if ($data['status']) {
            return ApiResponse::success([], $data['message'], $data['code']);
        }
        return ApiResponse::error($data['message'], $data['code']);

    }

    public function deleteUsersHandler($request): \Illuminate\Http\JsonResponse
    {
        $data = $this->mainRepository->deleteUsers($request);
        if ($data['status']) {
            return ApiResponse::success([], $data['message'], $data['code']);
        }
        return ApiResponse::error($data['message'], $data['code']);
    }

    public function addFilesHandler($request): \Illuminate\Http\JsonResponse
    {
        $data = $this->mainRepository->addFiles($request);
        if ($data['status']) {
            return ApiResponse::success([], $data['message'], $data['code']);
        }
        return ApiResponse::error($data['message'], $data['code']);
    }

    public function deleteFilesHandler($request): \Illuminate\Http\JsonResponse
    {
        $data = $this->mainRepository->deleteFiles($request);
        if ($data['status']) {
            return ApiResponse::success([], $data['message'], $data['code']);
        }
        return ApiResponse::error($data['message'], $data['code']);
    }

    // Crud operations :)
    public function storeHandler($request): \Illuminate\Http\JsonResponse
    {
        $data = $this->mainRepository->store($request);
        if ($data['status']) {
            return ApiResponse::success([], $data['message'], $data['code']);
        }
        return ApiResponse::error($data['message'], $data['code']);
    }

    public function updateHandler($request): \Illuminate\Http\JsonResponse
    {
            $data = $this->mainRepository->updateGroup($request);
            if ($data['status']) {
                return ApiResponse::success([new GroupResource($data['group'])], $data['message'], $data['code']);
            }
            return ApiResponse::error($data['message'],$data['code']);
    }
    public function destroyHandler($request): \Illuminate\Http\JsonResponse
    {
        $data = $this->mainRepository->destroy($request);
        if ($data['status']) {
            return ApiResponse::success([], $data['message'], $data['code']);
        }
        return ApiResponse::error($data['message'], $data['code']);

    }
}
