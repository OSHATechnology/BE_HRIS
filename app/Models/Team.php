<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $fillable = ['teamId', 'name', 'leadBy', 'createdBy', 'created_at', 'updated_at'];

    protected $primaryKey = 'teamId';

    public function createdByEmp()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'createdBy');
    }
    public function leadByEmp()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'leadBy');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%$search%")
            ->orWhereHas('leadByEmp', function ($q) use ($search) {
                $q->select('employeeId')->where('firstName', 'like', "%$search%")
                    ->orWhere('lastName', 'like', "%$search%");
            });
    }
}
