<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;
    protected $fillable = ['memberId','teamId', 'empId', 'assignedBy', 'joinedAt', 'created_at', 'updated_at'];

    protected $primaryKey = 'memberId';

    public function memberDetail()
    {
        return $this->hasMany(Employee::class, 'empId', 'employeeId');
    }

    public function assignedBy()
    {
        return $this->hasMany(Employee::class, 'assignedBy', 'employeeId');
    }

    public function team()
    {
        return $this->hasMany(Team::class, 'teamId');
    }
}
