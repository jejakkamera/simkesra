<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BantuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatanKarawang = [
            'Pangkalan', 'Ciampel', 'Klari', 'Cikampek', 'Tirtamulya', 'Jatisari',
            'Lemahabang', 'Telagasari', 'Rawamerta', 'Tempuran', 'Kutawaluya', 
            'Rengasdengklok', 'Pedes', 'Cibuaya', 'Tirtajaya', 'Batujaya', 'Pakisjaya',
            'Majalaya', 'Jayakerta', 'Cilamaya Kulon', 'Banyusari', 'Kotabaru',
            'Cilamaya Wetan', 'Purwasari', 'TelukJambe Barat', 'TelukJambe Timur',
            'Karawang Timur', 'Tegalwaru', 'Cilebar', 'Karawang Barat'
        ];

        // Menambahkan data Guru Ngaji untuk setiap kecamatan
        foreach ($kecamatanKarawang as $kecamatan) {
            DB::table('bantuan')->insert([
                'judul' => 'Guru Ngaji',
                'nominal' => 1500000, // Nominal tetap (misalnya, 1.2 juta)
                'wilayah' => strtolower($kecamatan), // Menggunakan nama kecamatan untuk wilayah
                'is_active' => true, // Status aktif
            ]);
        }
        //
        DB::table('bantuan')->insert([
            [
                'judul' => 'Forum DTA',
                'nominal' => 1200000,
                'wilayah' => null,
                'is_active' => true,
            ],
            [
                'judul' => 'Forum MI',
                'nominal' => 1200000,
                'wilayah' => null,
                'is_active' => true,
            ],
            [
                'judul' => 'Forum MTS',
                'nominal' => 1200000,
                'wilayah' => null,
                'is_active' => true,
            ],
            [
                'judul' => 'Forum TPQ',
                'nominal' => 1200000,
                'wilayah' => null,
                'is_active' => true,
            ],
            [
                'judul' => 'Forum AMIL',
                'nominal' => 1200000,
                'wilayah' => null,
                'is_active' => true,
            ],
            [
                'judul' => 'Forum Marbot',
                'nominal' => 1200000,
                'wilayah' => null,
                'is_active' => true,
            ],
        ]);
    }
}
