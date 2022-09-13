<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'partnerId',
        'name',
        'description',
        'resposibleBy',
        'phone',
        'address',
        'photo',
        'assignedBy',
        'joinedAt',
        'created_at',
        'updated_at'
    ];

    protected $primaryKey = 'partnerId';

    public function employee()
    {
        return $this->hasMany(Employee::class,'assignedBy', 'employeeId');
    }
}
