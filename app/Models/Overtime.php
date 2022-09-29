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
        return $this->hasOne(Employee::class, 'employeeId', 'assignedBy');
    }

    public static function changeStatus($id, $status)
    {
        $data = self::findOrFail($id);
        $data->isConfirmed = $status;
        $data->confirmedBy = auth()->user()->employeeId;
        return $data->save();
    }

    public static function confirmOT($overtimeId, $assignedById, $message = null)
    {
        try {
            $dataOT = self::findOrFail($overtimeId);
            $dataOT->isConfirmed = 1;
            $dataOT->confirmedBy = $assignedById;
            if (!$dataOT->save()) {
                return false;
            }
            // send notification
            $message = $message == null ? "Overtime request has been confirmed" : $message;
            Notification::sendNotification($dataOT->employeeId, $assignedById, $message, "overtime", $overtimeId);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
