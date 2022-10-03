<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InstalmentResource extends JsonResource
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
            'id' => $this->instalmentId,
            'loan' => ($this->loan) ? [
                'id' => $this->loan->loanId,
                'employee' => $this->loan->employee->firstName . " " . $this->loan->employee->lastName,
                'loan' => $this->loan->nominal,
                'loandDate' => $this->loan->loanDate,
                'paymentDate' => $this->loan->paymentDate,
                'status' => $this->loan->status,
            ] : "",
            'date' => $this->date,
            'nominal' => $this->nominal,
            'remainder' => $this->remainder,
        ];
    }
}
