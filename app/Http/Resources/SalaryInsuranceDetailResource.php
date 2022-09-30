<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalaryInsuranceDetailResource extends JsonResource
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
            'id' => $this->salaryInsId,
            'salary' => ($this->salary) ? [
                'idSalary' => $this->salary->salaryId,
                'employee' => $this->salary->emp->firstName,
                'basicSalary' => $this->salary->basic,
            ] : "",
            'insurance' => ($this->insuranceItem) ? [
                'idInsurance' => $this->insuranceItem->insItemId,
                'name' => $this->insuranceItem->name,
                'type' => $this->insuranceItem->type,
                'percent' => $this->insuranceItem->percent
            ] : "",
            'nominal' => $this->nominal,
            'date' => $this->date
        ];
    }
}
