<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Bibit;
use App\Models\Jabatan;
use App\Models\Kandang;
use App\Models\Karyawan;
use App\Models\Lokasi;
use App\Services\SalaryService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PayrollApiController extends Controller
{
    public function __construct(
        private readonly SalaryService $salaryService,
    ) {}

    public function penggajian(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'lokasi_id' => ['nullable', 'integer'],
            'kandang_id' => ['nullable', 'integer'],
            'bibit_id' => ['nullable', 'integer'],
            'karyawan_id' => ['nullable', 'integer'],
        ]);

        $report = $this->salaryService->calculateSalaryReport($filters);

        $data = $report['data'];
        if (! empty($filters['karyawan_id'])) {
            $data = collect($data)
                ->where('karyawan_id', (int) $filters['karyawan_id'])
                ->values()
                ->all();
        }

        return $this->ok('Data penggajian berhasil diambil.', $data, [
            'start_date' => $report['start_date'],
            'end_date' => $report['end_date'],
            'total_biaya' => (float) $report['total_biaya'],
            'total_karyawan' => count($data),
        ]);
    }

    public function karyawan(): JsonResponse
    {
        $karyawans = Karyawan::with(['jabatan', 'gajis'])
            ->where('status_aktif', true)
            ->orderBy('nama')
            ->get()
            ->map(fn (Karyawan $k) => [
                'karyawan_id' => $k->id,
                'nama' => $k->nama,
                'jabatan' => $k->jabatan->nama_jabatan,
                'gaji_pokok' => (float) ($k->gajis
                    ->where('berlaku_mulai', '<=', today())
                    ->sortByDesc('berlaku_mulai')
                    ->first()?->gaji_pokok ?? 0),
                'status_aktif' => $k->status_aktif,
            ]);

        return $this->ok('Data karyawan berhasil diambil.', $karyawans, [
            'total' => $karyawans->count(),
        ]);
    }

    public function absensi(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'karyawan_id' => ['nullable', 'integer'],
            'lokasi_id' => ['nullable', 'integer'],
            'kandang_id' => ['nullable', 'integer'],
            'bibit_id' => ['nullable', 'integer'],
        ]);

        $startDate = Carbon::parse($validated['start_date'] ?? now()->startOfMonth());
        $endDate = Carbon::parse($validated['end_date'] ?? now());

        $absensis = Absensi::with(['karyawan', 'jabatan', 'lokasi', 'kandang', 'bibit'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->when(! empty($validated['karyawan_id']), fn ($q, $v) => $q->where('karyawan_id', $v))
            ->when(! empty($validated['lokasi_id']), fn ($q, $v) => $q->where('lokasi_id', $v))
            ->when(! empty($validated['kandang_id']), fn ($q, $v) => $q->where('kandang_id', $v))
            ->when(! empty($validated['bibit_id']), fn ($q, $v) => $q->where('bibit_id', $v))
            ->orderBy('tanggal')
            ->get();

        return $this->ok('Data absensi berhasil diambil.', $absensis, [
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'total' => $absensis->count(),
        ]);
    }

    public function masterData(): JsonResponse
    {
        $data = [
            'jabatan' => Jabatan::orderBy('nama_jabatan')->get(['id', 'nama_jabatan']),
            'lokasi' => Lokasi::orderBy('nama_lokasi')->get(['id', 'nama_lokasi']),
            'kandang' => Kandang::with('lokasi:id,nama_lokasi')
                ->orderBy('nama_kandang')
                ->get(['id', 'lokasi_id', 'nama_kandang']),
            'bibit' => Bibit::with(['lokasi:id,nama_lokasi', 'kandang:id,nama_kandang'])
                ->orderByDesc('tanggal_masuk')
                ->get(),
        ];

        return $this->ok('Data master berhasil diambil.', $data);
    }

    private function ok(string $message, array|object $data, array $meta = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'meta' => $meta,
            'data' => $data,
        ]);
    }
}
