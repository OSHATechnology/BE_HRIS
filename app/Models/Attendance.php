<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['attendId', 'employeeId', 'attendanceStatusId', 'submitedAt', 'submitedById', 'typeInOut', 'timeAttend', 'created_at', 'updated_at'];

    protected $primaryKey = 'attendId';

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }
    public function attendanceStatus()
    {
        return $this->hasMany(AttendanceStatus::class);
    }
}
