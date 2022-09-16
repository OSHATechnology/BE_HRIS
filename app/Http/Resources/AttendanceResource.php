<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'id' => $this->attendId,
            'employee' => ($this->employee) ? [
                'empId' => $this->employee->employeeId,
                'name' => $this->employee->firstName . ' ' . $this->employee->lastName,
            ] : "", 
            'attendanceStatus' => ($this->attendanceStatus) ? [
                'attendanceStatusId' => $this->attendanceStatus->attendanceStatusId,
                'status' => $this->attendanceStatus->status,
            ] : '', 
            'submitedAt' => $this->submitedAt, 
            'submitedById' => ($this->submitedByIdEmp) ? [
                'empId' => $this->submitedByIdEmp->employeeId,
                'name' => $this->submitedByIdEmp->firstName . ' ' . $this->submitedByIdEmp->lastName,
            ] : "",  
            'typeInOut' => $this->typeInOut, 
            'timeAttend' => $this->timeAttend
        ];
    }
}
