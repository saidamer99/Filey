<?php

namespace App\Services\File;

use App\Helpers\ApiResponse;
use App\Http\Resources\FileDetailsResource;
use App\Http\Resources\FileResource;
use Illuminate\Http\JsonResponse;
use LaravelEasyRepository\Service;
use App\Repositories\File\FileRepository;

class FileServiceImplement extends Service implements FileService
{

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected $mainRepository;

    public function __construct(FileRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function detailsHandler($request): \Illuminate\Http\JsonResponse
    {
        $data = $this->mainRepository->details($request);
        if ($data['status']) {
            return ApiResponse::success(new FileDetailsResource($data['file']), '', $data['code']);

        }
        return ApiResponse::error($data['message'], $data['code']);
    }

    public function checkInHandler($request): JsonResponse
    {
        $data = $this->mainRepository->checkIn($request);
        if ($data['status']) {
            return ApiResponse::success($data['files'], $data['message'], $data['code']);

        }
        return ApiResponse::error($data['message'], $data['code']);
    }

    public function checkOutHandler($request): JsonResponse
    {
        $data = $this->mainRepository->checkOut($request);
        if ($data['status']) {
            return ApiResponse::success(['group_files'=>FileResource::collection($data['data'])], $data['message'], $data['code']);
        }
        return ApiResponse::error($data['message'], $data['code']);
    }
}
