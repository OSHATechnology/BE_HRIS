<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalaryResource extends JsonResource
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
            'id' => $this->salaryId,
            'employee' => ($this->emp) ? [
                'id' => $this->emp->employeeId,
                'name' => $this->emp->firstName . " " . $this->emp->lastName,
            ] : "",
            'role' => $this->role ?? "",
            'salary_date' => $this->salaryDate,
            'basic_salary' => $this->basic,
            'total_overtime' => $this->totalOvertime,
            'overtime_fee' => $this->overtimeFee,
            'allowance_item' => $this->allowance_item,
            'bonus' => $this->bonus,
            'gross' => $this->gross,
            'attendance' => $this->totalDeductionAttendance ?? 0,
            'loan' => $this->instalment ?? 0,
            'tax' => $this->tax ?? 0,
            'deduction_item' => $this->deduction_item ?? "",
            'total_deduction' => $this->total_deduction ?? 0,
            'net' => $this->net ?? 0,
        ];
    }
}
