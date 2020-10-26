<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class File extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "type" => $this->type,
            "width" => $this->width,
            "height" => $this->height,
            "url" => $this->url,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
