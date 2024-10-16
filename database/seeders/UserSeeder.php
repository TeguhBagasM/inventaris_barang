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
                'name' => 'Rifki HY',
                'email' => 'member@gmail.com',
                'email_verified_at' => now(),
                'level' => 'member',
                'password' => Hash::make('rifki123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Teguh Bagas M',
                'email' => 'teguhbagas2134@gmail.com',
                'email_verified_at' => now(),
                'level' => 'admin',
                'password' => Hash::make('bagmar21'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nur Auliya Putri',
                'email' => 'operator@gmail.com',
                'email_verified_at' => now(),
                'level' => 'operator',
                'password' => Hash::make('nuraul4'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
