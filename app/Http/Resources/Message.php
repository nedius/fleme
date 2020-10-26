<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Message extends JsonResource
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
            "user" => $this->user->getUserData(),
            "channel" => $this->channel,
            "content" => $this->content,
            "files" => File::collection($this->files),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
