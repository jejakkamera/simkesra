<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Skema as Bantuan;
use App\Models\Kelurahan;
use App\Models\WilayahKec;

class BantuanKelurahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $bantuans = Bantuan::all();

        foreach ($bantuans as $bantuan) {
            // Normalisasi nama wilayah bantuan
            $namaWilayah = strtolower(trim($bantuan->wilayah));

            // Cari kecamatan dengan nama mirip (hilangkan "kec." dan perbedaan spasi)
            $kecamatan = WilayahKec::query()
                ->whereRaw("
                    LOWER(REPLACE(REPLACE(nm_wil, 'Kecamatan', ''), 'Kec.', '')) LIKE ?
                ", ["%{$namaWilayah}%"])
                ->first();

            if (!$kecamatan) {
                echo "❌ Tidak ditemukan kecamatan untuk bantuan wilayah '{$bantuan->wilayah}'\n";
                continue;
            }

            // Ambil semua kelurahan di kecamatan itu
            $kelurahans = Kelurahan::where('id_kec', $kecamatan->id_wil)->get();

            foreach ($kelurahans as $kelurahan) {
                DB::table('bantuan_kelurahan')->updateOrInsert(
                    [
                        'bantuan_id' => $bantuan->id,
                        'kelurahan_id' => $kelurahan->id,
                    ],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }

            echo "✅ '{$bantuan->judul}' wilayah '{$bantuan->wilayah}' dikaitkan ke {$kelurahans->count()} kelurahan ({$kecamatan->nm_wil})\n";
        }
    }
}
