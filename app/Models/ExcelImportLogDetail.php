<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExcelImportLogDetail extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'import_log_id',
        'user_id',
        'status',
        'note',
    ];

    public function importLog()
    {
        return $this->belongsTo(ExcelImportLog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
