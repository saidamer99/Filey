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
class GroupResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'group-id' => $this->id,
            'group-name' => $this->name,
            'group-type' => $this->owner_id == 0 ? "public" : "private",
            'group-image' => $this->getFirstMediaUrl(),
        ];
    }
}
