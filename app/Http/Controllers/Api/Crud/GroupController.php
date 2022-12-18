<?php

namespace App\Http\Controllers\Api\Crud;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGroupRequest;
use App\Http\Requests\DeleteGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\User;
use App\Services\Group\GroupServiceImplement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Glide\Api\Api;

class GroupController extends Controller
{
    protected $model;
    protected User $user;
    protected GroupServiceImplement $mainService;

    public function __construct(GroupServiceImplement $mainService)
    {
        $this->mainService = $mainService;
        $this->middleware('group-owner')->except('store');
        $this->middleware('can-delete-group')->except('store', 'updateGroup');
    }

    public function store(CreateGroupRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->mainService->storeHandler($request);
    }

    public function updateGroup(UpdateGroupRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->mainService->updateHandler($request);

    }

    public function deleteGroup(DeleteGroupRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->mainService->destroyHandler($request);
    }


}
