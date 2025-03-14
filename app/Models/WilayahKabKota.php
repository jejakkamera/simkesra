<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WilayahKabKota extends Model
{
    use HasFactory;
     protected $table = 'wilayah_kab_kota';
    protected $primaryKey = 'id_wil';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id_wil', 'nm_wil', 'id_induk_wilayah'];

    public function provinsi()
    {
        return $this->belongsTo(WilayahProv::class, 'id_induk_wilayah', 'id_wil');
    }

    public function kecamatans()
    {
        return $this->hasMany(WilayahKec::class, 'id_induk_wilayah', 'id_wil');
    }
}
