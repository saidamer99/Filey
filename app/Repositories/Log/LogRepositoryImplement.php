<?php

namespace App\Repositories\Log;

use Carbon\Carbon;
use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Log;

class LogRepositoryImplement extends Eloquent implements LogRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Log $model)
    {
        $this->model = $model;
    }

    public function createReport($request): array
    {
        try {
            $from = Carbon::make($request->from)->toDateTime();
            $to = Carbon::make($request->to)->toDateTime();
            $report = Log::whereBetween('created_at', [$from,$to])->paginate(request()->per_page ?? 5);
            $data['status'] = true;
            $data['code'] = 200;
            $data['message'] = "Report build Successfully";
            $data['report'] = $report;
            return $data;
        } catch (\Throwable $exception) {
            $data['status'] = false;
            $data['code'] = 500;
            $data['message'] = "SomeThing Wrong happened";
            return $data;
        }
    }

    // Write something awesome :)
}
