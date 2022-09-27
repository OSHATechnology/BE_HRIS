<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $Permissions = [];
        foreach ($this->resource as $key => $permission) {
            foreach ($permission as $item) {
                $Permissions[$key][] = [
                    'id' => $item->permissionId,
                    'name' => $item->namePermission,
                    'description' => $item->description,
                    'tag' => $item->tag,
                    'slug' => $item->slug,
                ];
            }
        }
        return $Permissions;
    }
}
