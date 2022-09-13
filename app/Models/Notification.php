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
        return $this->hasMany(Employee::class, 'empId', 'employeeId');
    }
    public function senderBy()
    {
        return $this->hasMany(Employee::class, 'senderBy', 'employeeId');
    }
}
