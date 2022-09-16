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
        return $this->hasOne(Employee::class, 'employeeId', 'empId');
    }

    public function assignedByEmp()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'assignedBy');
    }

    public function team()
    {
        return $this->hasOne(Team::class, 'teamId', 'teamId');
    }
}
