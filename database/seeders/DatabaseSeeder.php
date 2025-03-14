<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = ['admin', 'kesra', 'unit', 'bank', 'teller','user'];

        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::create(['name' => $role]);
        }

        // \App\Models\User::factory(10)->create();

        $user = \App\Models\User::factory()->create([
            'id' => Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin@apps.apps',
            'role' => 'admin',
            'password' => bcrypt('newpassword'),
        ]);
        $user->assignRole('admin');

        $user = \App\Models\User::factory()->create([
            'id' => Str::uuid(),
            'name' => 'Kesra',
            'email' => 'kesra@apps.apps',
            'role' => 'kesra',
            'password' => bcrypt('newpassword'),
        ]);
        $user->assignRole('kesra');

        $this->call([
            SchoolTypesTableSeeder::class, // Tambahkan seeder di sini
            BantuanSeeder::class, // Tambahkan seeder di sini
            Wil::class, // Tambahkan seeder di sini
        ]);
    }
}
