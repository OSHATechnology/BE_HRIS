<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InsuranceResource extends JsonResource
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
            'id' => $this->insuranceId,
            'name' => $this->name,
            'company' => $this->companyName,
            'address' => $this->address,
            'data' => ($this->insurance_items) ? $this->insurance_items : ""
        ];
    }
}
