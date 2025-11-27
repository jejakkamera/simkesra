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
        //
        Schema::create('excel_import_logs', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');
            $table->string('user_id');
            $table->integer('periode_id');
            $table->integer('skema_id');
            $table->longText('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed']);
            $table->timestamps();
        });

        Schema::create('excel_import_log_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_log_id');
            $table->string('user_id')->nullable();
            // $table->uuid('user_id')->change()->nullable();
            $table->string('status'); // 'success' atau 'failed'
            $table->text('note')->nullable(); // Catatan tentang kegagalan atau keberhasilan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('excel_import_logs');
        Schema::dropIfExists('excel_import_log_details');
    }
};
