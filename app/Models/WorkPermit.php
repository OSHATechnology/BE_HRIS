<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkPermit extends Model
{
    use HasFactory;

    protected $fillable = ['workPermitId', 'employeeId', 'startAt', 'endAt', 'isConfirmed', 'confirmedBy', 'confirmedAt', 'create_at', 'updated_at'];

    protected $primaryKey = 'workPermitId';

    public function employee()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'employeeId');
    }

    public function confirmedByEmp()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'confirmedBy');
    }

    public function workPermitFiles()
    {
        return $this->hasMany(WorkPermitFile::class, 'workPermitId');
    }

    public static function confirmWP($wpid, $assignedById, $message = null)
    {
        try {
            $dataWP = self::findOrFail($wpid);
            $dataWP->isConfirmed = 1;
            $dataWP->confirmedBy = $assignedById;
            if (!$dataWP->save()) {
                return false;
            }
            // send notification
            $message = $message == null ? "work permit request has been confirmed" : $message;
            Notification::sendNotification($dataWP->employeeId, $assignedById, $message, "work permit", $wpid);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function declineWP($wid, $message = null)
    {
        try {
            $dataWp = self::findOrFail($wid);
            $dataWp->isConfirmed = 2;
            $dataWp->confirmedBy = auth()->user()->employeeId;
            $dataWp->message = $message;
            if (!$dataWp->save()) {
                return false;
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
