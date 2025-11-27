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
        Schema::create('bantuan_kelurahan', function (Blueprint $table) {
            $table->id();

            // Relasi ke bantuan
            $table->unsignedBigInteger('bantuan_id');
            $table->foreign('bantuan_id')
                ->references('id')
                ->on('bantuan')
                ->onDelete('cascade');

            // Relasi ke kelurahan
            $table->unsignedBigInteger('kelurahan_id');
            $table->foreign('kelurahan_id')
                ->references('id')
                ->on('kelurahans')
                ->onDelete('cascade');

            $table->timestamps();

            // Pastikan kombinasi unik (tidak ganda)
            $table->unique(['bantuan_id', 'kelurahan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bantuan_kelurahan');
    }
};
