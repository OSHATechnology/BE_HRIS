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

    public static function store($workPermitId, $name, $path)
    {
        $file = new WorkPermitFile;
        $file->workPermitId = $workPermitId;
        $file->name = $name;
        $file->path = $path;
        $file->save();
        return $file;
    }

    public static function updateFile($id ,$workPermitId, $name, $path)
    {
        $file = WorkPermitFile::findOrFail($id);
        $file->workPermitId = $workPermitId;
        $file->name = $name;
        $file->path = $path;
        $file->save();
        return $file;
    }
}
