<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['roleId','nameRole', 'description', 'created_at', 'updated_at'];

    protected $primaryKey = 'roleId';

    public function permission()
    {
        return $this->belongsToMany(Permission::class);
    }
}
