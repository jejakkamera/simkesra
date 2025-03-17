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
        Schema::create('user_bantuan', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->unsignedBigInteger('bantuan_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bantuan_id')->references('id')->on('bantuan')->onDelete('cascade');
            
            $table->primary(['user_id', 'bantuan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_bantuan');
    }
};
