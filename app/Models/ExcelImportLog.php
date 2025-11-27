<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelImportLog extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'file_path',
        'status',
        'user_id',
        'periode_id',
        'skema_id',
        'notes'
    ];

    public function details()
    {
        return $this->hasMany(ExcelImportLogDetail::class, 'import_log_id');
    }
}
