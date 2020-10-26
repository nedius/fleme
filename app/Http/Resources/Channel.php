<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Channel extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "name" => $this->name,
            "position" => $this->position,
            "type" => $this->type,
            "users" => User::collection($this->users),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            // "deleted_at" => $this->deleted_at,
        ];
    }
}
