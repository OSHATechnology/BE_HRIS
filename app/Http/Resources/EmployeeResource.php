<?php

namespace App\Http\Resources;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'employeeId' => $this->employeeId,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'name' => $this->firstName . " " . $this->lastName,
            'phone' => $this->phone,
            'email' => $this->email,
            'photo' => env('APP_URL') . '/' . $this->photo,
            'gender' => $this->gender,
            'birthDate' => $this->birthDate,
            'address' => $this->address,
            'city' => $this->city,
            'nation' => $this->nation,
            'role' => ($this->role) ? [
                'id' => $this->role->roleId,
                'role' => $this->role->nameRole,
            ] : '',
            'isActive' => ($this->isActive == 1) ? "true" : "false",
            'emailVerifiedAt' => $this->emailVerifiedAt,
            'joinedAt' => $this->joinedAt,
            'resignedAt' => $this->resignedAt,
            'statusHire' => ($this->statusHire) ? [
                'id' => $this->statusHire->StatusHireId,
                'status' => $this->statusHire->name,
            ] : '',
        ];
    }
}
