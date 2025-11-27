<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WilayahKec extends Model
{
    use HasFactory;
    protected $table = 'wilayah_kec';
    protected $primaryKey = 'id_wil';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id_wil', 'nm_wil', 'id_induk_wilayah'];

    public function kabKota()
    {
        return $this->belongsTo(WilayahKabKota::class, 'id_induk_wilayah', 'id_wil');
    }
}
