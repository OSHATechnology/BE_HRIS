<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Furlough extends Model
{
    use HasFactory;

    protected $fillable = ['furloughId', 'furTypeId', 'employeeId', 'startAt', 'endAt', 'isConfirmed', 'confirmedBy', 'lastFurloughAt', 'created_at', 'updated_at'];

    protected $primaryKey = 'furloughId';

    public function employee()
    {
        return $this->hasMany(Employee::class, 'employeeId');
    }

    public function confirmedBy()
    {
        return $this->hasMany(Employee::class, 'confirmedBy', 'employeeId');
    }

    public function furloughType()
    {
        return $this->hasOne(FurloughType::class, 'furTypeId');
    }
}
