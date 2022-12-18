<?php

namespace App\Services\User;

use App\Helpers\ApiResponse;
use App\Http\Resources\UserResource;
use LaravelEasyRepository\Service;
use App\Repositories\User\UserRepository;
use League\Glide\Api\Api;

class UserServiceImplement extends Service implements UserService
{

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected $mainRepository;

    public function __construct(UserRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function registerHandler($request): \Illuminate\Http\JsonResponse
    {
        $data = $this->mainRepository->register($request);
        if($data['status']) {
            return ApiResponse::success(['user' => new UserResource($data['user']),
                'token' => $data['token']], $data['message'], $data['code']);
        }else{
            return ApiResponse::error($data['message'],$data['code']);
        }
    }

    public function loginHandler($request): \Illuminate\Http\JsonResponse
    {
        $data = $this->mainRepository->login($request);
        if($data['status']){
            return ApiResponse::success(['user' => new UserResource($data['user']),
                'token' => $data['token']], $data['message'], $data['code']);
        }else{
            return ApiResponse::error($data['message'],$data['code']);
        }
    }
    public function logoutHandler(): \Illuminate\Http\JsonResponse
    {
        $data=$this->mainRepository->logout();
        if($data['status']){
            return ApiResponse::success([], $data['message'],$data['code']);
        }
        return ApiResponse::error($data['message'],$data['code']);
    }

}
