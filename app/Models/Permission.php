<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['permissionId','namePermission', 'description', 'tag', 'slug', 'created_at', 'updated_at'];

    protected $primaryKey = 'permissionId';

    public function role()
    {
        return $this->belongsToMany(Role::class);
    }
}
