<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
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
            'id' => $this->partnerId,
            'name' => $this->name,
            'description' => $this->description,
            'resposibleBy' => $this->resposibleBy,
            'phone' => $this->phone,
            'address' => $this->address,
            'photo' => env('APP_URL') . '/' . $this->photo,
            'assignedBy' => ($this->employee) ? [
                'empId' => $this->employee->employeeId,
                'name' => $this->employee->firstName . ' ' . $this->employee->lastName,
            ] : "",
            'joinedAt' => $this->joinedAt,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
