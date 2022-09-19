<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;

    protected $fillable = ['overtimeId', 'employeeId', 'startAt', 'endAt', 'assignedBy', 'created_at', 'updated_at'];

    protected $primaryKey = 'overtimeId';

    public function employee()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'employeeId');
    }
    public function assignedByEmp()
    {
        return $this->hasOne(Employee::class, 'employeeId','assignedBy');
    }
}
