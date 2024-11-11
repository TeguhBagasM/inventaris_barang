<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Teguh Bagas M',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'level' => 'admin',
                'password' => Hash::make('admin123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nur Auliya Putri',
                'email' => 'petugas1@gmail.com',
                'email_verified_at' => now(),
                'level' => 'petugas 1',
                'password' => Hash::make('petugas1'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rifki HY',
                'email' => 'petugas2@gmail.com',
                'email_verified_at' => now(),
                'level' => 'petugas 2',
                'password' => Hash::make('petugas2'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Amelia Chisha R',
                'email' => 'petugas3@gmail.com',
                'email_verified_at' => now(),
                'level' => 'petugas 3',
                'password' => Hash::make('petugas3'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Annisa Nanda',
                'email' => 'guru@gmail.com',
                'email_verified_at' => now(),
                'level' => 'guru',
                'password' => Hash::make('guru123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nurul Fatimah',
                'email' => 'siswa@gmail.com',
                'email_verified_at' => now(),
                'level' => 'siswa',
                'password' => Hash::make('siswa'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
