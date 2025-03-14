<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Period extends Model
{
    //
    use HasFactory;
    protected $table = 'periods';
    protected $fillable = [
        'name_period',
        'start_date',
        'end_date',
        'validate_date',
        'is_active',
    ];



    /**
     * Scope untuk mendapatkan periode aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
