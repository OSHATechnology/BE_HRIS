<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AllowanceResource extends JsonResource
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
            "id" => $this->allowanceId,
            "roleId" => $this->roleId,
            "role" => ($this->role) ? [
                "id" => $this->role->roleId,
                "role" => $this->role->nameRole
            ] : "",
            "typeAllowance" => ($this->typeOfAllowance) ? [
                "id" => $this->typeOfAllowance->typeId,
                "type" => $this->typeOfAllowance->name,
                "nominal" => $this->typeOfAllowance->nominal,
            ] : ""
        ];
    }
}
