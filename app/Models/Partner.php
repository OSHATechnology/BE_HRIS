<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Partner extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'partnerId',
        'name',
        'description',
        'resposibleBy',
        'phone',
        'address',
        'photo',
        'assignedBy',
        'joinedAt',
        'created_at',
        'updated_at'
    ];

    protected $primaryKey = 'partnerId';

    public function employee()
    {
        return $this->hasOne(Employee::class, 'employeeId', 'assignedBy');
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
        ];
    }
}
