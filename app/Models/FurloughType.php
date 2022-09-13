<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FurloughType extends Model
{
    use HasFactory;
    protected $fillable = ['furTypeId', 'name', 'type', 'max', 'created_at', 'updated_at'];

    protected $primaryKey = 'furTypeId';

    public function furlough()
    {
        return $this->hasOne(Furlough::class);
    }
}
