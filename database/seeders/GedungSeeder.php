<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GedungSeeder extends Seeder
{
    public function run()
    {
        DB::table('gedungs')->insert([
            [
                'nama_gedung' => 'Gedung A',
                'luas_gedung' => 150.25,
                'tahun_perolehan' => 2020,
                'nilai_bangunan' => 1000000000.00,
                'peruntukkan' => 'Kantor Ruang Guru',
                'gambar' => null,
                'keterangan' => 'Gedung A digunakan untuk Ruang Rapat Guru.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_gedung' => 'Gedung B',
                'luas_gedung' => 200.00,
                'tahun_perolehan' => 2012,
                'nilai_bangunan' => 1500000000.00,
                'peruntukkan' => 'Laboratorium',
                'gambar' => null,
                'keterangan' => 'Gedung B digunakan untuk laboratorium praktikum.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_gedung' => 'Gedung C',
                'luas_gedung' => 180.50,
                'tahun_perolehan' => 2015,
                'nilai_bangunan' => 1200000000.00,
                'peruntukkan' => 'Laboratorium',
                'gambar' => null,
                'keterangan' => 'Gedung C digunakan untuk Ruang Praktikum Jurusan RPL.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_gedung' => 'Lab Kimia',
                'luas_gedung' => 220.75,
                'tahun_perolehan' => 2018,
                'nilai_bangunan' => 1700000000.00,
                'peruntukkan' => 'Praktikum',
                'gambar' => null,
                'keterangan' => 'Lab Kimia digunakan untuk Praktikum jurusan Kimia Industri.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_gedung' => 'Gedung E',
                'luas_gedung' => 250.30,
                'tahun_perolehan' => 2020,
                'nilai_bangunan' => 2000000000.00,
                'peruntukkan' => 'Teori',
                'gambar' => null,
                'keterangan' => 'Gedung E digunakan untuk kelas teori kelas 11 dan 12.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_gedung' => 'Gedung F',
                'luas_gedung' => 300.50,
                'tahun_perolehan' => 2017,
                'nilai_bangunan' => 2500000000.00,
                'peruntukkan' => 'Teori',
                'gambar' => null,
                'keterangan' => 'Gedung F digunakan untuk ruang teori kelas 10.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_gedung' => 'Gedung G',
                'luas_gedung' => 275.45,
                'tahun_perolehan' => 2019,
                'nilai_bangunan' => 2300000000.00,
                'peruntukkan' => 'Teori',
                'gambar' => null,
                'keterangan' => 'Gedung G digunakan untuk teori.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
