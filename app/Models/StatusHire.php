<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusHire extends Model
{
    use HasFactory;

    protected $fillable = [
        'statusHireId',
        'name',
        'created_at',
        'updated_at'
    ];

    protected $primaryKey = 'statusHireId';
}
