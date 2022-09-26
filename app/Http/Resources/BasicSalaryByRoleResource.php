<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BasicSalaryByRoleResource extends JsonResource
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
          'id' => $this->basicSalaryByRoleId,
          'role' => ($this->role) ? [
            'id' => $this->role->roleId,
            'role' => $this->role->nameRole
          ] : "",
          'fee' => $this->fee
        ];
    }
}
