<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        School::create([
            'id' => 1,
            'name' => 'SIM Kesra Bantuan',
            'address' => 'Kab Karawang',
            'phone_number' => '081234567890',
            'logo' => '/storage/logo.png',
            'token' => 'token123',
        ]);
    }
}
