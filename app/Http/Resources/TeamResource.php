<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            'id' => $this->teamId,
            'name' => $this->name,
            'leadBy' => ($this->leadByEmp) ? [
                'id' => $this->leadByEmp->employeeId,
                'employee' => $this->leadByEmp->firstName . " " . $this->leadByEmp->lastName,
            ] : " ", 
            'createdBy' => ($this->createdByEmp) ? [
                'id' => $this->createdByEmp->employeeId,
                'employee' => $this->createdByEmp->firstName . " " . $this->createdByEmp->lastName,
            ] : " ",  
        ];
    }
}
