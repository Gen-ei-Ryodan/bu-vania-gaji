<?php

namespace Database\Seeders;

use App\Models\Kandang;
use App\Models\Lokasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KandangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokasis = Lokasi::all();

        foreach ($lokasis as $lokasi) {
            for ($i = 1; $i <= 3; $i++) {
                Kandang::create([
                    'lokasi_id' => $lokasi->id,
                    'nama_kandang' => "Kandang {$i} - {$lokasi->nama_lokasi}",
                ]);
            }
        }
    }
}
