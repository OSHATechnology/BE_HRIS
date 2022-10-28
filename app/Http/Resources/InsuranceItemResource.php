<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InsuranceItemResource extends JsonResource
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
            'id' => $this->insItemId,
            'insuranceId' => ($this->insurance) ? [
                'id' => $this->insurance->insuranceId,
                'name' => $this->insurance->name,
                'companyName' => $this->insurance->companyName,
            ] : "",
            'name' => $this->name,
            'type' => $this->type,
            'percent' => $this->percent,
            'roles' => $this->roles,
        ];
    }
}
