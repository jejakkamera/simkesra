<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Profile extends Model
{
    //
    use HasFactory;

    // Nama tabel (opsional, jika berbeda dari plural default)
    protected $table = 'profiles';

    protected $fillable = [
        'id',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'rt',
        'rw',
        'alamat',
        'desa',
        'kode_kecamatan',
        'kode_pos',
        'nama_ibu',
        'tempat_mengajar',
        'Alamat_mengajar',
        'fotoktp',
        'fotodiri',
    ];

    public function kecamatan(){
        return $this->belongsTo(WilayahKec::class, 'kode_kecamatan', 'id_wil');
    }

    public function pemenangans()
    {
        return $this->hasMany(Pemenangan::class, 'profile_id', 'id');
    }
    

}
