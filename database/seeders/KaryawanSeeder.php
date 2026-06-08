<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\Karyawan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = Jabatan::all();
        $gajiPokok = [
            'Mandor' => 5000000,
            'Sekretaris' => 4500000,
            'Admin' => 4000000,
            'Anak Kandang' => 3000000,
            'Gudang' => 3500000,
        ];

        $karyawans = [
            ['nama' => 'Budi Santoso', 'jabatan' => 'Mandor'],
            ['nama' => 'Siti Nurhaliza', 'jabatan' => 'Sekretaris'],
            ['nama' => 'Ahmad Fauzi', 'jabatan' => 'Admin'],
            ['nama' => 'Rina Wati', 'jabatan' => 'Anak Kandang'],
            ['nama' => 'Dedi Kurniawan', 'jabatan' => 'Anak Kandang'],
            ['nama' => 'Maya Sari', 'jabatan' => 'Gudang'],
            ['nama' => 'Joko Widodo', 'jabatan' => 'Anak Kandang'],
        ];

        foreach ($karyawans as $karyawan) {
            $jabatan = $jabatans->where('nama_jabatan', $karyawan['jabatan'])->first();
            Karyawan::create([
                'nama' => $karyawan['nama'],
                'jabatan_id' => $jabatan->id,
                'gaji_pokok' => $gajiPokok[$karyawan['jabatan']],
                'status_aktif' => true,
            ]);
        }
    }
}
