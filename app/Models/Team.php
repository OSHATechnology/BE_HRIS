<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $fillable = ['teamId','name', 'leadBy', 'createdBy', 'created_at', 'updated_at'];

    protected $primaryKey = 'teamId';

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }
}
