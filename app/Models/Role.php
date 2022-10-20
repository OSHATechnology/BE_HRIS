<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Role extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['roleId', 'nameRole', 'description', 'created_at', 'updated_at'];

    protected $primaryKey = 'roleId';

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'roleId', 'permissionId');
    }

    public function type_of_allowances()
    {
        return $this->belongsToMany(TypeOfAllowance::class, 'allowances', 'roleId', 'typeId');
    }

    public static function getIdsByName($name)
    {
        $role = self::where('nameRole', $name)->first();
        if ($role) {
            return $role->roleId;
        }
        return 2;
    }

    public function toSearchableArray()
    {
        return [
            'nameRole' => $this->nameRole,
        ];
    }

    public function basic_salary()
    {
        return $this->hasOne(BasicSalaryByRole::class, 'roleId', 'roleId');
    }
}
