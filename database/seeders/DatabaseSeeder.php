<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'login' => 'admin',
            'email' => 'admin@admin.com',
            'role' => 'Admin',
            'password' => Hash::make('123456'),
        ]);

        DB::table('users')->insert([
            'name' => 'Henrique',
            'login' => 'konac',
            'email' => 'konac@outlook.com.br',
            'role' => 'Admin',
            'password' => Hash::make('20511226a'),
        ]);
    }
}
