<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalaryAllowanceResource extends JsonResource
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
            "id" => $this->salaryAllowanceId,
            "salary" => ($this->salary) ? [
                "id" => $this->salary->salaryId,
                "employee" => $this->salary->emp->firstName . " " . $this->salary->emp->lastName,
                "basicSalary" => $this->salary->basic,
                "totalOvertime" => $this->salary->totalOvertime,
                "overtimeFee" => $this->salary->overtimeFee,
                "bonus" => $this->salary->bonus,
                "gross" => $this->salary->gross,
            ] : "",
            "allowanceName" => $this->allowanceName,
            "nominal" => $this->nominal,
        ];
    }
}
