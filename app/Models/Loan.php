<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'loanId',
        'empId',
        'name',
        'nominal',
        'loanDate',
        'paymentDate',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $primaryKey = 'loanId';

    public function employee()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'empId');
    }

    public static function getLastLoan($id)
    {
        $lastLoan = Loan::where("empId", $id)->orderBy('created_at', 'desc')->first();
        if ($lastLoan) {
            return $lastLoan->status;
        } else {
            return null;
        }
    }
}
