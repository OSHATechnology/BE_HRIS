<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Furlough extends Model
{
    use HasFactory;

    public const TYPESTATUS = [
        0 => 'Waiting For Approved',
        1 => 'Confirmed',
        2 => 'Rejected',
    ];

    protected $fillable = ['furloughId', 'furTypeId', 'employeeId', 'startAt', 'endAt', 'isConfirmed', 'confirmedBy', 'message', 'confirmedAt', 'lastFurloughAt', 'created_at', 'updated_at'];

    protected $primaryKey = 'furloughId';

    public function employee()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'employeeId');
    }

    public function confirmedByEmp()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'confirmedBy');
    }

    public function furloughType()
    {
        return $this->hasOne(FurloughType::class, 'furTypeId');
    }

    public static function getLastFurlough($employeeId)
    {
        $lastFurlough = Furlough::where('employeeId', $employeeId)->orderBy('startAt', 'desc')->first();
        if ($lastFurlough) {
            return $lastFurlough->startAt;
        } else {
            return null;
        }
    }
}
