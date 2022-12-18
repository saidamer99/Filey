<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\PaginationResource;
use App\Models\Group;
use App\Models\User;
use App\Services\Group\GroupServiceImplement;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public User $user;
    protected GroupServiceImplement $mainService;
    public function __construct(GroupServiceImplement $mainService)
    {
        $this->mainService=$mainService;
    }

    public function home(): \Illuminate\Http\JsonResponse
    {
       return $this->mainService->homeHandler();
    }

}
