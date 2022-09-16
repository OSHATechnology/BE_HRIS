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
}
