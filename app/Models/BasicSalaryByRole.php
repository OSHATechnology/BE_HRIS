<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicSalaryByRole extends Model
{
    use HasFactory;
    public $table = "basic_salary_by_roles";
    protected $fillable = [
        'basicSalaryByRoleId',
        'roleId',
        'fee',
        'created_at',
        'updated_at',
    ];

    protected $primaryKey = 'basicSalaryByRoleId';

    public function role()
    {
        return $this->hasOne(Role::class, 'roleId', 'roleId');
    }
}
