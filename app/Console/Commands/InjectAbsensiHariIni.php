<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\Bibit;
use App\Models\Karyawan;
use Illuminate\Console\Command;

class InjectAbsensiHariIni extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:inject {tanggal?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inject absensi untuk tanggal tertentu (default: 17 Desember 2025)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Tanggal default: 17 Desember 2025
        $tanggalInput = $this->argument('tanggal') ?? '2025-12-17';
        $tanggal = \Carbon\Carbon::parse($tanggalInput);

        $this->info("Menyuntikkan data absensi untuk tanggal: {$tanggal->format('d F Y')}");

        // Get all active employees
        $karyawans = Karyawan::where('status_aktif', true)->get();
        
        if ($karyawans->isEmpty()) {
            $this->error('Tidak ada karyawan aktif ditemukan!');
            return Command::FAILURE;
        }

        // Get all bibits
        $bibits = Bibit::all();
        
        if ($bibits->isEmpty()) {
            $this->error('Tidak ada data bibit ditemukan!');
            return Command::FAILURE;
        }

        $created = 0;
        $skipped = 0;

        foreach ($karyawans as $karyawan) {
            // Check if attendance already exists for this date
            $existing = Absensi::where('karyawan_id', $karyawan->id)
                ->where('tanggal', $tanggal->format('Y-m-d'))
                ->first();

            if ($existing) {
                $this->warn("Absensi untuk {$karyawan->nama} pada {$tanggal->format('d/m/Y')} sudah ada. Dilewati.");
                $skipped++;
                continue;
            }

            // Get random bibit
            $bibit = $bibits->random();
            
            // Random tipe absen (80% full, 20% half)
            $tipeAbsen = rand(1, 100) <= 80 ? 'full' : 'half';

            try {
                Absensi::create([
                    'karyawan_id' => $karyawan->id,
                    'jabatan_id' => $karyawan->jabatan_id,
                    'lokasi_id' => $bibit->lokasi_id,
                    'kandang_id' => $bibit->kandang_id,
                    'bibit_id' => $bibit->id,
                    'tipe_absen' => $tipeAbsen,
                    'tanggal' => $tanggal->format('Y-m-d'),
                ]);

                $created++;
                $this->info("✓ Absensi untuk {$karyawan->nama} berhasil dibuat ({$tipeAbsen})");
            } catch (\Exception $e) {
                $this->error("✗ Gagal membuat absensi untuk {$karyawan->nama}: {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info("Selesai! Dibuat: {$created}, Dilewati: {$skipped}");
        
        return Command::SUCCESS;
    }
}
