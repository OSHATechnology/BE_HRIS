<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['notifId', 'empId', 'name', 'content', 'type', 'senderBy', 'scheduleAt', 'status', 'created_at', 'updated_at'];

    protected $primaryKey = 'notifId';

    public function employee()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'empId');
    }
    public function senderByEmp()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'senderBy');
    }

    public static function sendNotification($empId, $senderBy, $content, $type, $scheduleAt = null)
    {
        $data = new self();
        $data->empId = $empId;
        $data->senderBy = $senderBy;
        $data->content = $content;
        $data->type = $type;
        if ($scheduleAt == null || $scheduleAt == "") {
            $data->scheduleAt = date('Y-m-d H:i:s');
        } else {
            $data->scheduleAt = $scheduleAt;
        }
        return $data->save();
    }
}
