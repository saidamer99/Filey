<?php

namespace App\Services\Log;

use App\Helpers\ApiResponse;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\ReportResource;
use LaravelEasyRepository\Service;
use App\Repositories\Log\LogRepository;

class LogServiceImplement extends Service implements LogService{

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(LogRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    public function createReportHandler($request): \Illuminate\Http\JsonResponse
    {
        $data=$this->mainRepository->createReport($request);
        if($data['status']){
            return ApiResponse::success(['report'=> ReportResource::collection($data['report'])],$data['message'],$data['code'],new PaginationResource($data['report']));
        }else{
            return ApiResponse::error($data['message'],$data['code']);
        }
    }

    // Define your custom methods :)
}
