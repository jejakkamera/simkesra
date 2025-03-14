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
        Schema::create('dashboard_information', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable(); // Deskripsi
            $table->enum('type', ['download', 'show_picture']); // Jenis informasi
            $table->string('file_path')->nullable(); // File path untuk download atau gambar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_information');
    }
};
