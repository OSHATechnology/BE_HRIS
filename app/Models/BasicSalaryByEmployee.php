<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicSalaryByEmployee extends Model
{
    use HasFactory;
    protected $fillable = [
        'basicSalaryByEmployeeId',
        'empId',
        'basicSalaryByRoleId',
        'fee',
        'created_at',
        'updated_at',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'empId');
    }

    public function salaryByRoleId()
    {
        return $this->hasOne(BasicSalaryByEmployee::class, 'basicSalaryByRoleId', 'basicSalaryByRoleId');
    }
}
