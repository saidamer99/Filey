<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\User\UserServiceImplement;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    protected UserServiceImplement $mainService;
    public function __construct(UserServiceImplement $mainService)
    {
        $this->mainService=$mainService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->mainService->registerHandler($request);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        return $this->mainService->loginHandler($request);
    }

    public function logout(): JsonResponse
    {
        return $this->mainService->logoutHandler();

    }
}
