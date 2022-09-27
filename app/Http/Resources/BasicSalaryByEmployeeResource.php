<?php

namespace App\Http\Resources;

use App\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class BasicSalaryByEmployeeResource extends JsonResource
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
            'id' => $this->basicSalaryByEmployeeId,
            'employee' => ($this->employee) ? [
                'id' => $this->employee->employeeId,
                'name' => $this->employee->firstName . " " . $this->employee->lastName
            ] : '',
            'role' => ($this->salaryByRole) ? [
                'id' => $this->salaryByRole->basicSalaryByRoleId,
                'role' => $this->salaryByRole->role->nameRole,
                'fee' => $this->salaryByRole->fee,
            ] : "",
            "fee" => $this->fee,
        ];
    }

}
