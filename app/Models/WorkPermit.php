<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkPermit extends Model
{
    use HasFactory;

    protected $fillable = ['workPermitId', 'employeeId', 'startAt', 'endAt', 'isConfirmed','confirmedBy', 'create_at', 'updated_at'];

    protected $primaryKey = 'workPermitId';

    public function employee()
    {
        return $this->hasMany(Employee::class, 'employeeId');
    }
    public function confirmedBy()
    {
        return $this->hasMany(Employee::class, 'confirmedBy', 'employeeId');
    }
}
