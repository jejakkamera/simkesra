<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    use HasFactory;

    protected $table = 'kelurahans';

    protected $primaryKey = 'id_kelurahan';
    public $incrementing = false; // karena id_kelurahan bukan auto-increment
    protected $keyType = 'string'; // jika id_kelurahan atau id_kec berupa string

    protected $fillable = [
        'id_kelurahan',
        'id_kec',
        'kemendagri_kelurahan_nama',
        'latitude',
        'longitude',
        'kode_pos',
    ];

    /**
     * Relasi ke Profile
     * Satu kelurahan bisa punya banyak profile.
     */
    public function profiles()
    {
        return $this->hasMany(Profile::class, 'id_kelurahan', 'id_kelurahan');
    }

    public function bantuans()
    {
        return $this->belongsToMany(Skema::class, 'bantuan_kelurahan', 'kelurahan_id', 'bantuan_id')
                    ->withTimestamps();
    }
}
