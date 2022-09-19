<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['roleId', 'nameRole', 'description', 'created_at', 'updated_at'];

    protected $primaryKey = 'roleId';

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'roleId', 'permissionId');
    }

    public static function getIdsByName($name)
    {
        $role = self::where('nameRole', $name)->first();
        if ($role) {
            return $role->roleId;
        }
        return 2;
    }
}
