<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeFamily extends Model
{
    use HasFactory;
    protected $fillable = [
        'idEmpFam',
        'empId',
        'identityNumber',
        'name',
        'statusId',
        'isAlive',
        'created_at',
        'updated_at'
    ];

    protected $primaryKey = 'idEmpFam';

    public function employee()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'employeeId');
    }

    public function status()
    {
        return $this->hasOne(Employee::class, 'empFamStatId', 'statusId');
    }
}
