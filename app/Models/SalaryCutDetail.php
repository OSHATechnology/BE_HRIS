<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryCutDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'salaryCutDetailsId',
        'salaryId',
        'totalAttendance',
        'attdFeeReduction',
        'loanId',
        'etc',
        'total',
        'net',
        'created_at', 
        'updated_at',
    ];

    protected $primaryKey = 'salaryCutDetailsId';

    public function salary()
    {
        return $this->hasOne(Salary::class, 'salaryId', 'salaryId');
    }

    public function loan()
    {
        return $this->hasOne(Loan::class, 'loanId', 'loanId');
    }
}
