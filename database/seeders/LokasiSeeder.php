<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokasis = [
            ['nama_lokasi' => 'Lokasi A - Blok Utara'],
            ['nama_lokasi' => 'Lokasi B - Blok Selatan'],
            ['nama_lokasi' => 'Lokasi C - Blok Timur'],
        ];

        foreach ($lokasis as $lokasi) {
            Lokasi::create($lokasi);
        }
    }
}
