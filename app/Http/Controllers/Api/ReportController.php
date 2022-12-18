<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateReportRequest;
use App\Services\Log\LogServiceImplement;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected LogServiceImplement $mainService;
    public function __construct(LogServiceImplement $mainService)
    {
        $this->mainService=$mainService;
    }

    public function createReport(CreateReportRequest $request): \Illuminate\Http\JsonResponse
    {
      return  $this->mainService->createReportHandler($request);
    }
}
