<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardInformation extends Model
{
    //
    use HasFactory;

    protected $table = 'dashboard_information';

    protected $fillable = [
        'description',
        'type',
        'file_path',
    ];
}
