<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'id' => $this->notifId,
            'employee' => ($this->employee) ? [
                'empId' => $this->employee->employeeId,
                'name' => $this->employee->firstName . ' ' . $this->employee->lastName,
            ] : '',
            'name' => $this->name,
            'content' => $this->content,
            'type' => $this->type,
            'senderBy' => ($this->senderByEmp) ? [
                'id' => $this->senderByEmp->employeeId,
                'name' => $this->senderByEmp->firstName . " " . $this->senderByEmp->lastName
            ] : "",
            'scheduleAt' => $this->scheduleAt
        ];
    }
}
