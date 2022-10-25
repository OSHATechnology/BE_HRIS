<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceItemRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'insItemId',
        'roleId',
    ];

    public function roles()
    {
        return $this->belongsTo(Role::class, 'roleId');
    }

    public function insuranceItems()
    {
        return $this->belongsTo(InsuranceItem::class, 'insItemId');
    }
}
