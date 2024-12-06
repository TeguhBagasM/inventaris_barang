<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TodolistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('to_do_lists')->insert([
            'judul' => 'testing',
            'deskripsi' => 'testing',
            'prioritas' => 'Tinggi',
            'status' => 'selesai',
            'user_id' => 1, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
