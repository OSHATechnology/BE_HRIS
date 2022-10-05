<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class TypeOfAllowance extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        "typeId",
        "name",
        "nominal",
        "created_at",
        "updated_at",
    ];

    protected $primaryKey = "typeId";

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'allowances', 'typeId', 'roleId');
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->name
        ];
    }
}
