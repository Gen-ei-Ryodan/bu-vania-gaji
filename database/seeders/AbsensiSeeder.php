<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Bibit;
use App\Models\Karyawan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawans = Karyawan::where('status_aktif', true)->get();
        $bibits = Bibit::all();

        // Generate attendance for last 30 days
        for ($day = 0; $day < 30; $day++) {
            $tanggal = now()->subDays($day);
            
            // Skip weekends (optional)
            if ($tanggal->isWeekend() && rand(0, 1)) {
                continue;
            }

            foreach ($karyawans as $karyawan) {
                // Random attendance (80% chance)
                if (rand(1, 100) <= 80) {
                    $bibit = $bibits->random();
                    $tipeAbsen = rand(0, 1) ? 'full' : 'half';

                    Absensi::create([
                        'karyawan_id' => $karyawan->id,
                        'jabatan_id' => $karyawan->jabatan_id,
                        'lokasi_id' => $bibit->lokasi_id,
                        'kandang_id' => $bibit->kandang_id,
                        'bibit_id' => $bibit->id,
                        'tipe_absen' => $tipeAbsen,
                        'tanggal' => $tanggal,
                    ]);
                }
            }
        }
    }
}
