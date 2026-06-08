<?php

namespace Database\Seeders;

use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GajiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawans = Karyawan::all();
        $owner = User::where('email', 'owner@example.com')->first();

        foreach ($karyawans as $karyawan) {
            // Create initial salary record
            Gaji::create([
                'karyawan_id' => $karyawan->id,
                'gaji_pokok' => $karyawan->gaji_pokok,
                'berlaku_mulai' => now()->subMonths(6),
                'catatan' => 'Gaji awal',
                'created_by' => $owner->id,
            ]);

            // Some employees have salary history
            if (rand(0, 1)) {
                Gaji::create([
                    'karyawan_id' => $karyawan->id,
                    'gaji_pokok' => $karyawan->gaji_pokok * 1.1, // 10% increase
                    'berlaku_mulai' => now()->subMonths(3),
                    'catatan' => 'Kenaikan gaji',
                    'created_by' => $owner->id,
                ]);
            }
        }
    }
}
