<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class UserBantuan extends Model
{
    //
    use HasFactory;

    // Nama tabel (opsional, jika berbeda dari plural default)
    protected $table = 'user_bantuan';

    protected $fillable = [
        'user_id',
        'bantuan_id',
        'bantuan_kelurahan_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bantuan()
    {
        return $this->belongsTo(Bantuan::class, 'bantuan_id');
    }

    public function bantuanKelurahan()
    {
        return $this->belongsTo(BantuanKelurahan::class, 'bantuan_kelurahan_id');
    }
}
