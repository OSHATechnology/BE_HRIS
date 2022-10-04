<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfAllowance extends Model
{
    use HasFactory;

    protected $fillable = [
        "typeId",
        "name",
        "nominal",
        "created_at",
        "updated_at",
    ];

    protected $primaryKey = "typeId";
}
