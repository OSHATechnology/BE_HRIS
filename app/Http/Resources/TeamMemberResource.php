<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamMemberResource extends JsonResource
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
            'id' => $this->memberId, 
            'team' => ($this->team) ? [
                'id' => $this->team->teamId,
                'name' => $this->team->name,
            ] : '', 
            'employee' => ($this->memberDetail) ? [
                'id' => $this->memberDetail->employeeId,
                'name' => $this->memberDetail->firstName . ' '. $this->memberDetail->lastName,
            ] : '', 
            'assignedBy' => ($this->assignedByEmp) ? [
                'id' => $this->assignedByEmp->employeeId,
                'name' => $this->assignedByEmp->firstName . ' '. $this->assignedByEmp->lastName,
            ] : '', 
            'joinedAt' => $this->joinedAt  
        ];
    }
}
