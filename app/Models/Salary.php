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
        'salaryDate',
        'basic',
        'totalOvertime',
        'overtimeFee',
        'bonus',
        'gross',
        'created_at',
        'updated_at'
    ];

    protected $primaryKey = 'salaryId';

    public const TAX = 5;
    public const PAYROLLDATE = '24';

    public function emp()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'empId');
    }

    public function insuranceItem()
    {
        return $this->belongsToMany(InsuranceItem::class, 'salary_insurance_details', 'salaryId', 'insItemId');
    }
}
