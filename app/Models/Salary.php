<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;
    protected $fillable = [
        'salaryId',
        'empId',
        'basic',
        'totalOvertime',
        'overtimeFee',
        'allowance',
        'bonus',
        'cutDetailsId',
        'gross',
        'net', 
        'created_at', 
        'updated_at'
    ];

    protected $primaryKey = 'salaryId';

    public function emplId()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'empIs');
    }

    public function CutId()
    {
        return $this->hasOne(SalaryCutDetail::class, 'salaryCutDetailsId', 'cutDetailsId');
    }

    
            
}
