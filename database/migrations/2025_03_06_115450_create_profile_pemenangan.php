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
        Schema::create('profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('rt');
            $table->string('rw');
            $table->text('alamat');
            $table->string('desa');
            $table->string('kode_kecamatan');
            $table->string('kode_pos', 10);
            $table->string('nama_ibu');
            $table->string('tempat_mengajar');
            $table->string('Alamat_mengajar');
            $table->string('fotoktp')->nullable();
            $table->string('fotodiri')->nullable();
            $table->timestamps();
        });
                   
        Schema::create('pemenangan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('profile_id');
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->integer('idbantuan');
            $table->integer('periode');
            $table->string('no_rekening', 50)->nullable();
            $table->string('jenis_rekening')->nullable();
            $table->string('tipe_rekening')->nullable();
            $table->string('id_verif_teller')->nullable();
            $table->timestamp('tanggal_verif_teller')->nullable();
            $table
                ->enum('verif_teller', ['-', 'Selesai'])
                ->default('-')
                ->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemenangan');
        Schema::dropIfExists('profiles');
    }
};
