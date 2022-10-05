<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    use HasFactory;

    protected $fillable = [
        "allowanceId",
        "roleId",
        "typeId",
    ];

    protected $primaryKey = "allowanceId";

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function typeOfAllowance()
    {
        return $this->belongsTo(TypeOfAllowance::class);
    }
}
