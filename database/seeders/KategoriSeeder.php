<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategoris')->insert([
            [
                'nama' => 'Peralatan Elektronik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Peralatan Kantor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Buku dan Literatur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Peralatan Olahraga',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Peralatan Kelas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Furnitur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Peralatan Laboratorium',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Alat Tulis Kantor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
