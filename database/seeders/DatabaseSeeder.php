<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            JabatanSeeder::class,
            LokasiSeeder::class,
            KandangSeeder::class,
            KaryawanSeeder::class,
            BibitSeeder::class,
            GajiSeeder::class,
            AbsensiSeeder::class,
            PeriodDataSeeder::class,
        ]);
    }
}
