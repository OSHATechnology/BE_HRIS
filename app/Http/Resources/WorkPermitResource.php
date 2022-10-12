<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkPermitResource extends JsonResource
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
            'id' => $this->workPermitId,
            'employeeId' => ($this->employee) ? [
                'id' => $this->employee->employeeId,
                'name' => $this->employee->firstName . " " . $this->employee->lastName,
            ] : "",
            'file' => $this->workPermitFiles,
            'startAt' => $this->startAt,
            'endAt' =>$this->endAt,
            'isConfirmed' => $this->isConfirmed,
            'confirmedBy' =>  ($this->confirmedByEmp) ? [
                'id' => $this->confirmedByEmp->employeeId,
                'name' => $this->confirmedByEmp->firstName . " " . $this->confirmedByEmp->lastName,
            ] : "",            
        ];
    }
}
