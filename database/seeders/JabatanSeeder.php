<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            ['nama_jabatan' => 'Mandor'],
            ['nama_jabatan' => 'Sekretaris'],
            ['nama_jabatan' => 'Admin'],
            ['nama_jabatan' => 'Anak Kandang'],
            ['nama_jabatan' => 'Gudang'],
        ];

        foreach ($jabatans as $jabatan) {
            Jabatan::create($jabatan);
        }
    }
}
