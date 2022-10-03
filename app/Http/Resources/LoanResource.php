<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
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
            'loanId' => $this->loanId,
            'employee'=> ($this->employee) ? [
                'id' => $this->employee->employeeId,
                'name' => $this->employee->firstName . " " . $this->employee->lastName
            ] : "",
            'name' => $this->name,
            'nominal' => $this->nominal,
            'loanDate' => $this->loanDate,
            'paymentDate' => $this->paymentDate,
            'status' => $this->status,
        ];
    }
}
