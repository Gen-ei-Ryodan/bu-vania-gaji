<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Gaji;
use App\Models\Karyawan;
use Carbon\Carbon;

class SalaryService
{
    /**
     * Calculate daily salary
     */
    public function calculateDailySalary(float $gajiPokok): float
    {
        return $gajiPokok / 30;
    }

    /**
     * Calculate half day salary
     */
    public function calculateHalfDaySalary(float $gajiPokok): float
    {
        return $this->calculateDailySalary($gajiPokok) / 2;
    }

    /**
     * Get active salary for employee
     */
    public function getActiveSalary(Karyawan $karyawan, ?Carbon $date = null): ?Gaji
    {
        $date = $date ?? now();

        return $karyawan->gajis()
            ->where('berlaku_mulai', '<=', $date)
            ->latest('berlaku_mulai')
            ->first();
    }

    /**
     * Calculate salary for employee in a period
     * Returns an array of salary calculations (split by salary changes)
     */
    public function calculateSalaryForPeriod(
        Karyawan $karyawan,
        Carbon $startDate,
        Carbon $endDate,
        array $filters = []
    ): array {
        // Fetch absensis in the period
        $absensisQuery = Absensi::where('karyawan_id', $karyawan->id)
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if (isset($filters['lokasi_id']) && $filters['lokasi_id']) {
            $absensisQuery->where('lokasi_id', $filters['lokasi_id']);
        }

        if (isset($filters['kandang_id']) && $filters['kandang_id']) {
            $absensisQuery->where('kandang_id', $filters['kandang_id']);
        }

        // Filter by bibit_id if provided
        if (isset($filters['bibit_id']) && $filters['bibit_id']) {
            $absensisQuery->where('bibit_id', $filters['bibit_id']);
        }

        $absensis = $absensisQuery->orderBy('tanggal')->get();

        if ($absensis->isEmpty()) {
            // Return one empty record with current active salary
            $gajiAktif = $this->getActiveSalary($karyawan, $endDate);

            return [[
                'karyawan_id' => $karyawan->id,
                'nama' => $karyawan->nama,
                'jabatan' => $karyawan->jabatan->nama_jabatan,
                'gaji_pokok' => $gajiAktif ? $gajiAktif->gaji_pokok : 0,
                'total_hari_full' => 0,
                'total_hari_half' => 0,
                'total_gaji' => 0,
            ]];
        }

        // Group absensis by Gaji record
        $groupedData = [];

        foreach ($absensis as $absensi) {
            $gaji = $this->getActiveSalary($karyawan, $absensi->tanggal);

            // Use Gaji ID as key, or 'no_salary' if none
            $key = $gaji ? $gaji->id : 'no_salary';
            $gajiPokok = $gaji ? $gaji->gaji_pokok : 0;

            if (! isset($groupedData[$key])) {
                $groupedData[$key] = [
                    'karyawan_id' => $karyawan->id,
                    'nama' => $karyawan->nama,
                    'jabatan' => $karyawan->jabatan->nama_jabatan,
                    'gaji_pokok' => $gajiPokok,
                    'total_hari_full' => 0,
                    'total_hari_half' => 0,
                    'total_gaji' => 0,
                    'start_date' => $absensi->tanggal, // Track min date for this group
                    'end_date' => $absensi->tanggal,   // Track max date
                ];
            }

            // Update min/max date
            if ($absensi->tanggal < $groupedData[$key]['start_date']) {
                $groupedData[$key]['start_date'] = $absensi->tanggal;
            }
            if ($absensi->tanggal > $groupedData[$key]['end_date']) {
                $groupedData[$key]['end_date'] = $absensi->tanggal;
            }

            // Add counts
            if ($absensi->tipe_absen == 'full') {
                $groupedData[$key]['total_hari_full']++;
            } else {
                $groupedData[$key]['total_hari_half']++;
            }
        }

        // Calculate totals
        foreach ($groupedData as &$data) {
            $gajiHarian = $this->calculateDailySalary($data['gaji_pokok']);
            $gajiHalfDay = $this->calculateHalfDaySalary($data['gaji_pokok']);
            $data['total_gaji'] = ($data['total_hari_full'] * $gajiHarian) + ($data['total_hari_half'] * $gajiHalfDay);

            // Optional: Format dates for display if needed
            // $data['periode'] = $data['start_date']->format('d M') . ' - ' . $data['end_date']->format('d M');
        }

        return array_values($groupedData);
    }

    /**
     * Calculate salary report for multiple employees
     */
    public function calculateSalaryReport(
        array $filters = []
    ): array {
        $startDate = isset($filters['start_date'])
            ? Carbon::parse($filters['start_date'])
            : now()->startOfMonth();

        $endDate = isset($filters['end_date'])
            ? Carbon::parse($filters['end_date'])
            : now()->endOfMonth();

        $query = Karyawan::query()
            ->with(['jabatan']);

        // Apply filters
        $hasAbsensiFilters = ! empty($filters['lokasi_id']) || ! empty($filters['kandang_id']) || ! empty($filters['bibit_id']);
        if ($hasAbsensiFilters) {
            $query->whereHas('absensis', function ($q) use ($filters, $startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);

                if (! empty($filters['lokasi_id'])) {
                    $q->where('lokasi_id', $filters['lokasi_id']);
                }

                if (! empty($filters['kandang_id'])) {
                    $q->where('kandang_id', $filters['kandang_id']);
                }

                if (! empty($filters['bibit_id'])) {
                    $q->where('bibit_id', $filters['bibit_id']);
                }
            });
        }

        if (isset($filters['jabatan_id']) && $filters['jabatan_id']) {
            $query->where('jabatan_id', $filters['jabatan_id']);
        }

        if (isset($filters['nama_pegawai']) && $filters['nama_pegawai']) {
            $query->where('nama', 'like', '%'.$filters['nama_pegawai'].'%');
        }

        $karyawans = $query->get();
        $results = [];

        foreach ($karyawans as $karyawan) {
            $employeeResults = $this->calculateSalaryForPeriod($karyawan, $startDate, $endDate, $filters);

            foreach ($employeeResults as $result) {
                // If filtering by bibit_id, only include rows with actual data
                if (isset($filters['bibit_id']) && $filters['bibit_id'] && $result['total_gaji'] == 0) {
                    continue;
                }

                // Only add if there is data or if we want to show empty rows (but with split logic, empty rows might be confusing if duplicated)
                // However, calculateSalaryForPeriod returns a single empty row if no absensi.
                // We should keep it.
                $results[] = $result;
            }
        }

        return [
            'data' => $results,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'total_biaya' => collect($results)->sum('total_gaji'),
        ];
    }

    /**
     * Create new salary record with audit trail
     */
    public function createSalaryRecord(
        Karyawan $karyawan,
        float $gajiPokok,
        Carbon $berlakuMulai,
        ?string $catatan = null,
        ?int $createdBy = null
    ): Gaji {
        return Gaji::create([
            'karyawan_id' => $karyawan->id,
            'gaji_pokok' => $gajiPokok,
            'berlaku_mulai' => $berlakuMulai,
            'catatan' => $catatan ?? 'Perubahan gaji',
            'created_by' => $createdBy ?? auth()->id(),
        ]);
    }
}
