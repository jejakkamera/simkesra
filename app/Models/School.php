<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class School extends Model
{
    //
    use HasFactory;

    // Nama tabel (opsional, jika berbeda dari plural default)
    protected $table = 'schools';

    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'logo',
        'token',
    ];

}
