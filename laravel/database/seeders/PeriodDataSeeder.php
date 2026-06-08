<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Bibit;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedDesember2025();
        $this->seedJanuari2026();
    }

    private function seedDesember2025(): void
    {
        $karyawans = Karyawan::where('status_aktif', true)->get();
        $bibits = Bibit::all();
        $owner = User::where('email', 'owner@example.com')->first();
        
        // Desember 2025 - 22 hari kerja (exclude weekends)
        $startDate = Carbon::create(2025, 12, 1);
        $endDate = Carbon::create(2025, 12, 31);
        
        foreach ($karyawans as $karyawan) {
            $hadirDays = 0;
            
            for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                // Skip weekends and simulate some absences
                if ($date->isWeekend()) continue;
                
                // 85% attendance rate for December
                if (rand(1, 100) <= 85) {
                    $bibit = $bibits->random();
                    $tipeAbsen = rand(0, 1) ? 'full' : 'half';
                    
                    // Check if attendance record already exists
                    $existingAbsensi = Absensi::where('karyawan_id', $karyawan->id)
                        ->where('tanggal', $date)
                        ->first();
                    
                    if (!$existingAbsensi) {
                        Absensi::create([
                            'karyawan_id' => $karyawan->id,
                            'jabatan_id' => $karyawan->jabatan_id,
                            'lokasi_id' => $bibit->lokasi_id,
                            'kandang_id' => $bibit->kandang_id,
                            'bibit_id' => $bibit->id,
                            'tipe_absen' => $tipeAbsen,
                            'tanggal' => $date,
                        ]);
                        
                        $hadirDays++;
                    }
                }
            }
            
            // Create December salary record
            $gajiDesember = $karyawan->gaji_pokok;
            if ($hadirDays < 22) {
                $gajiDesember = $gajiDesember * ($hadirDays / 22);
            }
            
            Gaji::create([
                'karyawan_id' => $karyawan->id,
                'gaji_pokok' => round($gajiDesember, 2),
                'berlaku_mulai' => $startDate,
                'catatan' => 'Gaji Desember 2025',
                'created_by' => $owner->id,
            ]);
        }
    }

    private function seedJanuari2026(): void
    {
        $karyawans = Karyawan::where('status_aktif', true)->get();
        $bibits = Bibit::all();
        $owner = User::where('email', 'owner@example.com')->first();
        
        // Januari 2026 - 21 hari kerja (exclude weekends)
        $startDate = Carbon::create(2026, 1, 1);
        $endDate = Carbon::create(2026, 1, 31);
        
        foreach ($karyawans as $karyawan) {
            $hadirDays = 0;
            
            for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                // Skip weekends and simulate some absences
                if ($date->isWeekend()) continue;
                
                // 90% attendance rate for January (new year motivation)
                if (rand(1, 100) <= 90) {
                    $bibit = $bibits->random();
                    $tipeAbsen = rand(0, 1) ? 'full' : 'half';
                    
                    // Check if attendance record already exists
                    $existingAbsensi = Absensi::where('karyawan_id', $karyawan->id)
                        ->where('tanggal', $date)
                        ->first();
                    
                    if (!$existingAbsensi) {
                        Absensi::create([
                            'karyawan_id' => $karyawan->id,
                            'jabatan_id' => $karyawan->jabatan_id,
                            'lokasi_id' => $bibit->lokasi_id,
                            'kandang_id' => $bibit->kandang_id,
                            'bibit_id' => $bibit->id,
                            'tipe_absen' => $tipeAbsen,
                            'tanggal' => $date,
                        ]);
                        
                        $hadirDays++;
                    }
                }
            }
            
            // Create January salary record
            $gajiJanuari = $karyawan->gaji_pokok;
            if ($hadirDays < 21) {
                $gajiJanuari = $gajiJanuari * ($hadirDays / 21);
            }
            
            Gaji::create([
                'karyawan_id' => $karyawan->id,
                'gaji_pokok' => round($gajiJanuari, 2),
                'berlaku_mulai' => $startDate,
                'catatan' => 'Gaji Januari 2026',
                'created_by' => $owner->id,
            ]);
        }
    }
}