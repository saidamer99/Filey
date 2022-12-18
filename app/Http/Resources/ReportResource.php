<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $email
 * @property mixed $created_at
 * @property mixed $url
 * @property mixed $method
 * @property mixed $request_body
 * @property mixed $response
 */
class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'url'=>$this->url,
            'method'=>$this->method,
            'request_body'=>$this->request_body,
            'response'=>$this->response,
            'date'=>Carbon::make($this->created_at)->toDateString(),
        ];
    }
}
