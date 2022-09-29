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
        'bonus',
        'cutDetailsId',
        'gross',
        'net', 
        'created_at', 
        'updated_at'
    ];

    protected $primaryKey = 'salaryId';

    public function emp()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'empId');
    }    
            
}
