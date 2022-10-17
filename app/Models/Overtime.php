<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'overtimeId', 'employeeId', 'startAt', 'endAt', 'assignedBy',
        'isConfirmed', 'confirmedBy', 'message', 'created_at', 'updated_at'
    ];

    protected $primaryKey = 'overtimeId';

    public const STATUS = [
        0 => 'Pending',
        1 => 'Confirmed',
        2 => 'Rejected'
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'employeeId');
    }

    public function assignedByEmp()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'assignedBy');
    }

    public static function confirmed()
    {
        return self::where('isConfirmed', 1)->get();
    }

    public function scopeConfirmed($query)
    {
        return $query->where('isConfirmed', 1);
    }

    public function scopeWithoutPending($query)
    {
        return $query->where('isConfirmed', '!=', 0);
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

    public static function declineOT($overtimeId, $message = null)
    {
        try {
            $dataOT = self::findOrFail($overtimeId);
            $dataOT->isConfirmed = 2;
            $dataOT->confirmedBy = auth()->user()->employeeId;
            $dataOT->message = $message;
            if (!$dataOT->save()) {
                return false;
            }
            // send notification
            // $message = $message == null ? "Overtime request has been declined" : $message;
            // Notification::sendNotification($dataOT->employeeId, auth()->user()->employeeId, $message, "overtime", $overtimeId);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
