<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Pemenangan extends Model
{
    //
    use HasFactory;

    // Nama tabel (opsional, jika berbeda dari plural default)
    protected $table = 'pemenangan';

    protected $fillable = [
        'id',
        'profile_id',
        'idbantuan',
        'periode',
        'no_rekening',
        'jenis_rekening',
        'tipe_rekening',
        'id_verif_teller',
        'tanggal_verif_teller',
        'verif_teller',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'periode', 'id');
    }

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'idbantuan', 'id');
    }

}
