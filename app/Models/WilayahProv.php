<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WilayahProv extends Model
{
    use HasFactory;
    protected $table = 'wilayah_prov';
    protected $primaryKey = 'id_wil';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id_wil', 'nm_wil'];

    public function kabKotas()
    {
        return $this->hasMany(WilayahKabKota::class, 'id_induk_wilayah', 'id_wil');
    }
}
