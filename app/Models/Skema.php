<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Skema extends Model
{
    //
    use HasFactory;

    // Nama tabel (opsional, jika berbeda dari plural default)
    protected $table = 'bantuan';

    protected $fillable = [
        'judul',
        'nominal',
        'wilayah',
        'is_active',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_bantuan', 'bantuan_id', 'user_id')
                    ->withTimestamps();
    }

}