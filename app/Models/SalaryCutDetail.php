<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryCutDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'salaryCutDetailsId',
        'totalAttendance',
        'attdFeeReduction',
        'bpjs',
        'loan',
        'etc',
        'total',
        'created_at', 
        'updated_at',
    ];

    protected $primaryKey = 'salaryCutDetailsId';
}
