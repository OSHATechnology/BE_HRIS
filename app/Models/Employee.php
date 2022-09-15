<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Gate;

class Employee extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'employeeId',
        'firstName',
        'lastName',
        'phone',
        'email',
        'password',
        'photo',
        'gender',
        'birthDate',
        'address',
        'city',
        'nation',
        'roleId',
        'isActive',
        'emailVerifiedAt',
        'joinedAt',
        'resignedAt',
        'statusHireId',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $primaryKey = 'employeeId';

    public function role()
    {
        return $this->hasOne(Role::class, 'roleId', 'roleId');
    }

    public function statusHire()
    {
        return $this->hasOne(StatusHire::class, 'statusHireId', 'statusHireId');
    }

    public function hasPermissionTo($permission)
    {
        return $this->role->permissions->contains('slug', $permission);
    }

    public function furloughs()
    {
        return $this->hasMany(Furlough::class, 'employeeId');
    }
}
