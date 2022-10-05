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

    public static function getLastRemainder($loanId)
    {
        $lastRemainder = Instalment::where('loanId', $loanId)->orderBy('date', 'desc')->first();
        if ($lastRemainder) {
            return $lastRemainder->remainder;
        } else {
            return null;
        }
    }

    public static function getLastNominal($loanId)
    {
        $lastRemainder = Instalment::where('loanId', $loanId)->orderBy('date', 'desc')->first();
        if ($lastRemainder) {
            return $lastRemainder->nominal;
        } else {
            return null;
        }
    }
}
