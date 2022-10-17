<?php

namespace App\Http\Resources;

use App\Models\Overtime;
use Illuminate\Http\Resources\Json\JsonResource;

class OvertimeResource extends JsonResource
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
            'id' => $this->overtimeId,
            'employeeId' => ($this->employee) ? [
                'id' => $this->employee->employeeId,
                'name' => $this->employee->firstName . " " . $this->employee->lastName,
            ] : "",
            'startAt' => $this->startAt,
            'endAt' => $this->endAt,
            'assignedBy' => ($this->assignedByEmp) ? [
                'id' => $this->assignedByEmp->employeeId,
                'name' => $this->assignedByEmp->firstName . " " . $this->assignedByEmp->lastName,
            ] : "",
            'status' => Overtime::STATUS[$this->isConfirmed] ? Overtime::STATUS[$this->isConfirmed] : "",
        ];
    }
}
