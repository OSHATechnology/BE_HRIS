<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['permissionId', 'namePermission', 'description', 'tag', 'slug', 'created_at', 'updated_at'];

    protected $primaryKey = 'permissionId';

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permissionId', 'roleId');
    }
}
