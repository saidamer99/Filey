<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileDetailsRequest;
use App\Http\Resources\FileDetailsResource;
use App\Models\File;
use App\Models\Group;
use App\Services\File\FileServiceImplement;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected FileServiceImplement $mainService;
    public function __construct(FileServiceImplement $mainService)
    {
        $this->mainService=$mainService;
    }

    public function details(FileDetailsRequest $request)
    {
      return $this->mainService->detailsHandler($request);
    }

   public function checkIn(Request $request): \Illuminate\Http\JsonResponse
   {
          return $this->mainService->checkInHandler($request);
   }
   public function checkOut(Request $request): \Illuminate\Http\JsonResponse
   {
        return $this->mainService->checkOutHandler($request);

   }
}
