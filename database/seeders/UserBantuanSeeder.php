<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserBantuan;

class UserBantuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua id bantuan
        $bantuans = DB::table('bantuan')->pluck('id')->toArray();

        $kecamatanKarawang = [
            'Pangkalan', 'Ciampel', 'Klari', 'Cikampek', 'Tirtamulya', 'Jatisari',
            'Lemahabang', 'Telagasari', 'Rawamerta', 'Tempuran', 'Kutawaluya', 
            'Rengasdengklok', 'Pedes', 'Cibuaya', 'Tirtajaya', 'Batujaya', 'Pakisjaya',
            'Majalaya', 'Jayakerta', 'Cilamaya Kulon', 'Banyusari', 'Kotabaru',
            'Cilamaya Wetan', 'Purwasari', 'TelukJambe Barat', 'TelukJambe Timur',
            'Karawang Timur', 'Tegalwaru', 'Cilebar', 'Karawang Barat'
        ];

        foreach ($kecamatanKarawang as $index => $kecamatan) {
            $slugKecamatan = Str::slug($kecamatan);

            $user = \App\Models\User::factory()->create([
                'id'       => Str::uuid(),
                'name'     => $slugKecamatan . '-gurungaji@apps.apps',
                'email'    => $slugKecamatan . '-gurungaji@apps.apps',
                'role'     => 'unit',
                'password' => bcrypt('gurungaji@apps.apps'),
            ]);

            // Ambil bantuan_id sesuai index, looping kalau lebih banyak user dari bantuan
            $bantuanId = $bantuans[$index % count($bantuans)];

            \App\Models\UserBantuan::create([
                'bantuan_id' => $bantuanId,
                'user_id'    => $user->id,
            ]);
        }

        // Tambahan user khusus yang kamu buat manual
        $usersTambahan = [
            'DTA@apps.apps',
            'MI@apps.apps',
            'MTS@apps.apps',
            'TPQ@apps.apps',
            'AMIL@apps.apps',
            'Marbot@apps.apps'
        ];

        foreach ($usersTambahan as $index => $email) {
            $user = \App\Models\User::factory()->create([
                'id'       => Str::uuid(),
                'name'     => $email,
                'email'    => $email,
                'role'     => 'unit',
                'password' => bcrypt($email),
            ]);

            // Ambil bantuan_id berdasarkan urutan
            $bantuanId = $bantuans[$index % count($bantuans)];

            \App\Models\UserBantuan::create([
                'bantuan_id' => $bantuanId,
                'user_id'    => $user->id,
            ]);
        }

        $this->command->info('UserBantuanSeeder selesai!');
    }
}
