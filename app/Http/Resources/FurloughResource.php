<?php

namespace App\Http\Resources;

use App\Models\Furlough;
use Illuminate\Http\Resources\Json\JsonResource;

class FurloughResource extends JsonResource
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
            'id' => $this->furloughId,
            'furType' => ($this->furloughType) ? $this->furloughType->name : $this->furTypeId,
            'employee' => ($this->employee) ? [
                'empId' => $this->employee->employeeId,
                // dd($employee->employeeId);
                'name' => $this->employee->firstName . ' ' . $this->employee->lastName,
            ] : '',
            'startAt' => $this->startAt,
            'endAt' => $this->endAt,
            'confirmedBy' => ($this->confirmedByEmp) ? [
                'empId' => $this->confirmedByEmp->employeeId,
                'name' => $this->confirmedByEmp->firstName . ' ' . $this->confirmedByEmp->lastName,
            ] : '',
            'status' => Furlough::TYPESTATUS[$this->isConfirmed],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
