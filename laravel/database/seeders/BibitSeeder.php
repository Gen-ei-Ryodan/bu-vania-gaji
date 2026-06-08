<?php

namespace Database\Seeders;

use App\Models\Bibit;
use App\Models\Kandang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BibitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kandangs = Kandang::all();
        $jenisBibit = ['Ayam Broiler', 'Ayam Petelur', 'Ayam Kampung'];

        // 1 kandang = 1 bibit
        foreach ($kandangs as $kandang) {
            $tanggalMasuk = now()->subDays(rand(10, 60));
            
            Bibit::create([
                'lokasi_id' => $kandang->lokasi_id,
                'kandang_id' => $kandang->id,
                'jenis_bibit' => $jenisBibit[array_rand($jenisBibit)],
                'tanggal_masuk' => $tanggalMasuk,
            ]);
        }
    }
}
