<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeFamilyStatus extends Model
{
    use HasFactory;

    protected $fillable = ['empFamStatId', 'status', 'created_at', 'updated_at'];
    
    protected $primaryKey = 'empFamStatId';
}
