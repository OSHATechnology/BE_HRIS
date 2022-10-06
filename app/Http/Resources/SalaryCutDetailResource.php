<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalaryCutDetailResource extends JsonResource
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
            'id' => $this->salaryCutDetailsId,
            'salary' => ($this->salary) ? [
                'id' => $this->salary->salaryId,
                'employee' => $this->salary->emp->firstName . " " . $this->salary->emp->lastName,
                'basic' => $this->salary->basic,
                'totalOvertime' => $this->salary->totalOvertime,
                'overtimeFee' => $this->salary->overtimeFee,
                'bonus' => $this->salary->bonus,
                'gross' => $this->salary->gross
            ] : "",
            'totalAttendance' => $this->totalAttendance,
            'attdFeeReduction' => $this->attdFeeReduction,
            'loan' => $this->loan,
            'etc' => $this->etc,
            'total' => $this->total,
            'net' => $this->net,
        ];
    }
}
