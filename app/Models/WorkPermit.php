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
}
