<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $name
 * @property mixed $media
 * @property mixed $owner_id
 * @property mixed $id
 * @method media()
 * @method getFirstMediaUrl()
 */
class FileDetailsResource extends JsonResource
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
            'file-type' => !is_null($this->media()->first())?$this->media()->first()->mime_type:"not defined",
            'file-size' => $this->getFirstMediaUrl(),
            'file-image' => $this->getFirstMediaUrl(),
        ];
    }
}
