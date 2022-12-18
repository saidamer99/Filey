<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $name
 * @property mixed $media
 * @property mixed $owner_id
 * @property mixed $status
 * @method media()
 * @method getFirstMediaUrl()
 */
class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        if(count($this->media)==0)
//        {
//            return null;
//        }
        return [
            'file-id' => $this->id,
            'file-name' => !is_null($this->media()->first())?$this->media()->first()->filename:"not defined",
            'file-status' => getFileStatus($this),
            'file-image' => $this->getFirstMediaUrl(),
        ];
    }
}
