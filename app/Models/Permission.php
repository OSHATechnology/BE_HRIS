<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Permission extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['permissionId', 'namePermission', 'description', 'tag', 'slug', 'created_at', 'updated_at'];

    protected $primaryKey = 'permissionId';

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permissionId', 'roleId');
    }

    public function toSearchableArray()
    {
        return [
            'namePermission' => $this->namePermission,
        ];
    }
}
