<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pemenangan', function (Blueprint $table) {
            //
            $table->enum('status', ['Diajukan', 'Disetujui', 'Ditolak'])->default('Diajukan')->after('verif_teller');
            $table->text('keterangan')->nullable()->after('status');
            $table->string('foto_kegiatan_1')->nullable()->after('keterangan');
            $table->string('foto_kegiatan_2')->nullable()->after('foto_kegiatan_1');
            $table->string('foto_surat_tugas')->nullable()->after('foto_kegiatan_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemenangan', function (Blueprint $table) {
            $table->dropColumn(['status', 'keterangan', 'foto_kegiatan_1', 'foto_kegiatan_2', 'foto_surat_tugas']);
        });
    }
};
