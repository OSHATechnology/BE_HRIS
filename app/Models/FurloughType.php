<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class FurloughType extends Model
{
    use HasFactory, Searchable;
    protected $fillable = ['furTypeId', 'name', 'type', 'max', 'created_at', 'updated_at'];

    protected $primaryKey = 'furTypeId';

    public function toSearchableArray()
    {
        return [
            'name' => $this->name
        ];
    }
}
