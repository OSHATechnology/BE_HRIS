<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class InsuranceItem extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'insItemId',
        'insuranceId',
        'name',
        'type',
        'percent',
        'created_at',
        'updated_at'
    ];

    protected $primaryKey = 'insItemId';

    public function insurance()
    {
        return $this->hasOne(Insurance::class, 'insuranceId', 'insuranceId');
    }

    public function salary()
    {
        return $this->belongsToMany(Salary::class, 'salary_insurance_details', 'insItemId', 'salaryId');
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->name
        ];
    }
}
