<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryAllowance extends Model
{
    use HasFactory;

    protected $fillable = [
        "salaryAllowanceId",
        "salaryId",
        "allowanceName",
        "nominal",
    ];

    protected $primaryKey = "salaryAllowanceId";

    public function salary()
    {
        return $this->hasOne(Salary::class, 'salaryId', 'salaryId');
    }
}
