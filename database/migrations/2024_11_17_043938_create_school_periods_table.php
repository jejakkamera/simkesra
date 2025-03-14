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
        Schema::create('schools', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name')->unique(); // Nama sekolah
            $table->string('address')->nullable(); // Alamat sekolah
            $table->string('phone_number')->nullable(); // Nomor telepon
            $table->string('logo')->nullable(); // Logo telepon
            $table->string('token')->nullable(); // Website sekolah
            $table->timestamps(); // Created at dan Updated at
        });

        Schema::create('periods', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name_period'); // Nama periode (contoh: Tahun Ajaran 2024/2025)
            $table->date('start_date'); // Tanggal mulai periode
            $table->date('end_date'); // Tanggal selesai periode
            $table->date('validate_date'); // Tanggal selesai periode
            $table->boolean('is_active')->default(false); // Status periode aktif (true/false)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
        Schema::dropIfExists('periods');
    }
};
