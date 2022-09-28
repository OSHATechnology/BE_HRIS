<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeFamily extends Model
{
    use HasFactory;

    public const TYPESTATUS = [
        0 => 'Die',
        1 => 'Alive',
    ];

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
        return $this->hasOne(Employee::class, 'employeeId', 'empId');
    }

    public function status()
    {
        return $this->hasOne(EmployeeFamilyStatus::class, 'empFamStatId', 'statusId');
    }
}
