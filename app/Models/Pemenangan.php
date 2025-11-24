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
    protected $primaryKey = 'id'; // ini default, tapi bisa eksplisit
    public $incrementing = false; // karena pakai UUID
    protected $keyType = 'string'; // UUID adalah string
    protected $casts = [
        'id' => 'string',
    ];

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
        'status',             // ✅ Tambahan
        'keterangan',         // ✅ Tambahan
        'foto_kegiatan_1',    // ✅ Tambahan
        'foto_kegiatan_2',    // ✅ Tambahan
        'foto_surat_tugas',   // ✅ Tambahan
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

     public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'Diajukan' => '<span class="badge bg-warning text-dark">Diajukan</span>',
            'Disetujui' => '<span class="badge bg-success">Disetujui</span>',
            'Ditolak' => '<span class="badge bg-danger">Ditolak</span>',
            default => '<span class="badge bg-secondary">Tidak Diketahui</span>',
        };
    }

}
