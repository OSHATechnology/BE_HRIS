<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkPermitFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'fileId',
        'workPermitId',
        'name',
        'path',
        'created_at',
        'updated_at',
    ];

    protected $primaryKey = 'fileId';

    public function workPermits()
    {
        return $this->hasOne(WorkPermit::class, 'workPermitId', 'workPermitId');
    }
}
