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
        Schema::table('user_bantuan', function (Blueprint $table) {
            if (!Schema::hasColumn('user_bantuan', 'bantuan_kelurahan_id')) {
                $table->unsignedBigInteger('bantuan_kelurahan_id')->nullable()->after('bantuan_id');

                $table->foreign('bantuan_kelurahan_id')
                    ->references('id')
                    ->on('bantuan_kelurahan')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_bantuan', function (Blueprint $table) {
            if (Schema::hasColumn('user_bantuan', 'bantuan_kelurahan_id')) {
                $table->dropForeign(['bantuan_kelurahan_id']);
                $table->dropColumn('bantuan_kelurahan_id');
            }

            // Revert kalau mau restore kelurahan_id
            $table->unsignedBigInteger('kelurahan_id')->nullable()->after('bantuan_id');
        });
    }
};
