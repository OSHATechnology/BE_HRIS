<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Attendance extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['attendId', 'employeeId', 'attendanceStatusId', 'submitedAt', 'submitedById', 'typeInOut', 'timeAttend', 'created_at', 'updated_at'];

    protected $primaryKey = 'attendId';

    const STATUS_WORK = 1;

    public function employee()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'employeeId');
    }
    public function submitedByIdEmp()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'employeeId');
    }
    public function attendanceStatus()
    {
        return $this->hasOne(AttendanceStatus::class, 'attendanceStatusId', 'attendanceStatusId');
    }

    public function toSearchableArray()
    {
        return [
            'firstName' => $this->employee->firstname,
        ];
    }

    public static function insertAttend($employeeId, $attendanceStatusId, $submitedById, $typeInOut, $timeAttend = null)
    {
        $data = new self();
        $data->employeeId = $employeeId;
        $data->attendanceStatusId = $attendanceStatusId;
        $data->submitedById = $submitedById;
        if ($timeAttend == null || $timeAttend == "") {
            $data->timeAttend = date('Y-m-d H:i:s');
        } else {
            $data->timeAttend = $timeAttend;
        }

        $data->typeInOut = $typeInOut;
        return $data->save();
    }
}
