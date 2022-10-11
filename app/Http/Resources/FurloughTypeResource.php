<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FurloughTypeResource extends JsonResource
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
            'id' => $this->furTypeId,
            'name' => $this->name,
            'type' => $this->type,
            'max' => $this->max,
        ];
    }
}
