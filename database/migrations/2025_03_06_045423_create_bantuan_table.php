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
        Schema::create('bantuan', function (Blueprint $table) {
            $table->id(); // Primary key, id otomatis
            $table->string('judul'); // Judul bantuan (misalnya: "Cara Menggunakan Aplikasi")
            $table->unsignedBigInteger('nominal'); // Judul bantuan (misalnya: "Cara Menggunakan Aplikasi")
            $table->string('wilayah')->nullable(); // Kategori (misalnya: "Akun", "Pengaturan", dll.)
            $table->boolean('is_active')->default(true); // Status aktif/inaktif
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bantuan');
    }
};
