<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'insItemId',
        'insuranceId',
        'name',
        'type',
        'percent',
        'created_at',
        'updated_at'
    ];

    protected $primaryKey = 'insItemId';

    public function insurance()
    {
        return $this->hasOne(Insurance::class, 'insuranceId', 'insuranceId');
    }
}
