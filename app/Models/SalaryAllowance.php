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
        "created_at",
        "updated_at",
    ];

    protected $primaryKey = "salaryAllowanceId";

    public function salary()
    {
        return $this->hasOne(Salary::class, 'salaryId', 'salaryId');
    }
}
