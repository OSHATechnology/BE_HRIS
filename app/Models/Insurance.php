<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Insurance extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['insuranceId', 'name', 'companyName', 'address'];

    protected $primaryKey = 'insuranceId';

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'companyName' => $this->companyName
        ];
    }
}
