<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryInsuranceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'salaryInsId',
        'salaryId',
        'insItemId',
        'nominal',
        'date',
    ];

    protected $primaryKey = 'salaryInsId';

    public function insuranceItem()
    {
        return $this->belongsTo(InsuranceItem::class, 'insItemId', 'insItemId');
    }

    public function salary()
    {
        return $this->belongsTo(Salary::class, 'salaryId', 'salaryId');
    }
}
