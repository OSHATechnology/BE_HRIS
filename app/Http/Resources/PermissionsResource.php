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
        $i = 0;
        foreach ($this->resource as $key => $permission) {
            $data = [
                'id' => ++$i,
                "group" => $key,
                "data" => $this->toChildArray($permission),
            ];
            $Permissions[] = $data;
        }
        return $Permissions;
    }

    public function toChildArray($data)
    {
        foreach ($data as $key => $value) {
            $data[$key] = [
                'id' => $value->permissionId,
                'namePermission' => $value->namePermission,
                'description' => $value->description,
                'tag' => $value->tag,
                'slug' => $value->slug,
            ];
        }
        return $data;
    }
}
