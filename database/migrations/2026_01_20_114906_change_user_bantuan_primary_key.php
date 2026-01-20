<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Mengubah primary key dari (user_id, bantuan_id) menjadi auto-increment id
     * karena sekarang user bisa memilih beberapa kelurahan untuk satu bantuan yang sama.
     */
    public function up(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Hapus duplicate terlebih dahulu agar tidak error
        DB::statement('
            DELETE t1 FROM user_bantuan t1
            INNER JOIN user_bantuan t2 
            WHERE t1.created_at < t2.created_at 
            AND t1.user_id = t2.user_id 
            AND t1.bantuan_id = t2.bantuan_id
        ');

        // Drop primary key lama
        DB::statement('ALTER TABLE user_bantuan DROP PRIMARY KEY');

        // Tambahkan auto-increment id sebagai primary key
        DB::statement('ALTER TABLE user_bantuan ADD id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST');

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop id column dan primary key
        DB::statement('ALTER TABLE user_bantuan DROP COLUMN id');

        // Hapus duplicate lagi sebelum restore primary key
        DB::statement('
            DELETE t1 FROM user_bantuan t1
            INNER JOIN user_bantuan t2 
            WHERE t1.created_at < t2.created_at 
            AND t1.user_id = t2.user_id 
            AND t1.bantuan_id = t2.bantuan_id
        ');

        // Restore primary key lama
        DB::statement('ALTER TABLE user_bantuan ADD PRIMARY KEY (user_id, bantuan_id)');

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
