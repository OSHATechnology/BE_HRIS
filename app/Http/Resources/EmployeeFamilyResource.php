<?php

namespace App\Http\Resources;

use App\Models\EmployeeFamily;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeFamilyResource extends JsonResource
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
            'id' => $this->idEmpFam,
            'employee' => ($this->employee) ? [
                'id' => $this->employee->employeeId,
                'name' => $this->employee->firstName . " " . $this->employee->lastName
            ] : "",
            'identityNumber' => $this->identityNumber,
            'name' => $this->name,
            'status' => ($this->status) ? [
                'id' => $this->status->empFamStatId,
                'status' => $this->status->status,
            ] : "",
            'isAlive' => EmployeeFamily::TYPESTATUS[$this->isAlive],
        ];
    }
}
