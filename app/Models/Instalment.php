<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instalment extends Model
{
    use HasFactory;

    protected $fillable = [
        'instalmentId',
        'loanId',
        'date',
        'nominal',
        'remainder',
        'created_at',
        'updated_at'
    ];

    protected $primaryKey = 'instalmentId';

    public function loan()
    {
        return $this->hasOne(Loan::class, 'loanId', 'loanId');
    }
}
