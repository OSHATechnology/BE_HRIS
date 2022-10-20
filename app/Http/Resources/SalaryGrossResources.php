<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalaryGrossResources extends JsonResource
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
            'empId' => $this->employeeId,
            // 'empName' => $this->employee->name,
            // 'salaryDate' => $this->salaryDate,
            // 'basicSalary' => $this->basicSalary,
            // 'totalOvertime' => $this->totalOvertime,
            // 'totalInsentive' => $this->totalInsentive,
            // 'totalBonus' => $this->totalBonus,
            // 'total' => $this->total,
        ];
    }
}
