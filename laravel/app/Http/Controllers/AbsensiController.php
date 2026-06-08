<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAbsensiRequest;
use App\Http\Requests\UpdateAbsensiRequest;
use App\Models\Absensi;
use App\Models\Bibit;
use App\Models\Jabatan;
use App\Models\Kandang;
use App\Models\Karyawan;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Absensi::with(['karyawan', 'jabatan', 'lokasi', 'kandang', 'bibit']);

        if ($request->filled('karyawan_id')) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        // Filter: Jabatan
        if ($request->filled('jabatan_id')) {
            $query->where('jabatan_id', $request->jabatan_id);
        }

        // Filter: Lokasi
        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        // Filter: Kandang
        if ($request->filled('kandang_id')) {
            $query->where('kandang_id', $request->kandang_id);
        }

        // Filter: Bibit
        if ($request->filled('bibit_id')) {
            $query->where('bibit_id', $request->bibit_id);
        }

        // Filter: Tipe Absen
        if ($request->filled('tipe_absen')) {
            $query->where('tipe_absen', $request->tipe_absen);
        }

        // Filter: Range Tanggal
        // Default: jika user tidak set filter tanggal, set ke hari ini
        if (!$request->filled('start_date') && !$request->filled('end_date')) {
            $request->merge([
                'start_date' => now()->format('Y-m-d'),
                'end_date' => now()->format('Y-m-d'),
            ]);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Filter: Tanggal Bibit
        if ($request->filled('tanggal_bibit')) {
            $query->whereHas('bibit', function($q) use ($request) {
                $q->whereDate('tanggal_masuk', $request->tanggal_bibit);
            });
        }

        $absensis = $query->latest('tanggal')->paginate(20);
        $karyawans = Karyawan::with('jabatan')->orderBy('nama')->get();
        $jabatans = Jabatan::all();
        $lokasis = Lokasi::all();
        $kandangs = Kandang::with('bibit')->get();
        $bibits = Bibit::with('kandang')->get();

        return view('absensi.index', compact('absensis', 'karyawans', 'jabatans', 'lokasis', 'kandangs', 'bibits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karyawans = Karyawan::where('status_aktif', true)->with('jabatan')->get();
        $lokasis = Lokasi::all();
        $kandangs = Kandang::all();
        // Hanya tampilkan bibit dari kandang yang punya bibit (1 kandang = 1 bibit)
        $bibits = Bibit::with('kandang')->get();

        return view('absensi.create', compact('karyawans', 'lokasis', 'kandangs', 'bibits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAbsensiRequest $request)
    {
        try {
            // Auto-fill lokasi and kandang from bibit
            $bibit = Bibit::findOrFail($request->bibit_id);
            $lokasiId = $bibit->lokasi_id;
            $kandangId = $bibit->kandang_id;
            $tanggal = $request->tanggal;

            // Get arrays from request
            $karyawanIds = $request->input('karyawan', []);
            $jabatanIds = $request->input('jabatan', []);
            $tipeAbsens = $request->input('tipe_absen', []);

            // Check for existing full day absensi records before creating
            // Allow multiple half day records but prevent duplicate full day records
            $existingFullDayAbsensi = Absensi::whereIn('karyawan_id', $karyawanIds)
                ->where('tanggal', $tanggal)
                ->where('tipe_absen', 'full')
                ->with('karyawan')
                ->get();

            if ($existingFullDayAbsensi->isNotEmpty()) {
                $karyawanNames = $existingFullDayAbsensi->pluck('karyawan.nama')->implode(', ');
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Absensi Full Day untuk {$karyawanNames} pada tanggal " . date('d/m/Y', strtotime($tanggal)) . " sudah ada. Silakan pilih tanggal lain atau edit absensi yang sudah ada.");
            }

            // Check for existing half day records to provide warning/info
            $existingHalfDayAbsensi = Absensi::whereIn('karyawan_id', $karyawanIds)
                ->where('tanggal', $tanggal)
                ->where('tipe_absen', 'half')
                ->with(['karyawan', 'kandang'])
                ->get();

            $halfDayWarning = '';
            if ($existingHalfDayAbsensi->isNotEmpty()) {
                $halfDayDetailsByKaryawan = [];
                foreach ($existingHalfDayAbsensi as $absensi) {
                    $halfDayDetailsByKaryawan[$absensi->karyawan_id][] = "{$absensi->kandang->nama_kandang}";
                }

                $halfDayWarnings = [];
                foreach ($existingHalfDayAbsensi->groupBy('karyawan_id') as $karyawanId => $items) {
                    $karyawanNama = optional($items->first()->karyawan)->nama ?? 'Karyawan';
                    $kandangList = $halfDayDetailsByKaryawan[$karyawanId] ?? [];
                    $count = $items->count();

                    if ($count >= 2) {
                        $halfDayWarnings[] = "{$karyawanNama} sudah Half Day {$count}x (kandang: " . implode(', ', $kandangList) . "), tidak bisa tambah lagi di tanggal ini";
                    } else {
                        $halfDayWarnings[] = "{$karyawanNama} sudah Half Day {$count}x (kandang: " . implode(', ', $kandangList) . "), masih bisa tambah 1x lagi di tanggal ini";
                    }
                }

                $halfDayWarning = 'Peringatan: ' . implode('. ', $halfDayWarnings) . '.';
            }

            // Create absensi records for each karyawan
            $createdCount = 0;
            $errors = [];

            foreach ($karyawanIds as $index => $karyawanId) {
                try {
                    Absensi::create([
                        'karyawan_id' => $karyawanId,
                        'jabatan_id' => $jabatanIds[$index] ?? null,
                        'lokasi_id' => $lokasiId,
                        'kandang_id' => $kandangId,
                        'bibit_id' => $request->bibit_id,
                        'tipe_absen' => $tipeAbsens[$index] ?? 'full',
                        'tanggal' => $tanggal,
                    ]);
                    $createdCount++;
                } catch (\Illuminate\Database\QueryException $e) {
                    if ($e->getCode() == 23000) {
                        $karyawan = Karyawan::find($karyawanId);
                        $karyawanNama = $karyawan ? $karyawan->nama : 'karyawan';
                        $tipeAbsen = $tipeAbsens[$index] ?? 'full';
                        if ($tipeAbsen === 'full') {
                            $errors[] = "Absensi Full Day untuk {$karyawanNama} pada tanggal " . date('d/m/Y', strtotime($tanggal)) . " sudah ada.";
                        } else {
                            $errors[] = "Terjadi kesalahan duplikat untuk {$karyawanNama} (Half Day). Mungkin sudah ada absensi di kandang ini.";
                        }
                    } else {
                        $errors[] = "Terjadi kesalahan saat menyimpan data karyawan ke-" . ($index + 1);
                    }
                } catch (\Exception $e) {
                    $errors[] = "Terjadi kesalahan saat menyimpan data karyawan ke-" . ($index + 1) . ": " . $e->getMessage();
                }
            }

            if (!empty($errors)) {
                $errorMessage = implode(' ', $errors);
                if ($createdCount > 0) {
                    $errorMessage = "Berhasil menyimpan {$createdCount} absensi. " . $errorMessage;
                }
                return redirect()->back()
                    ->withInput()
                    ->with('error', $errorMessage);
            }

            $message = $createdCount > 1 
                ? "{$createdCount} absensi berhasil ditambahkan." 
                : "Absensi berhasil ditambahkan.";

            // Add half day warning if applicable
            if (!empty($halfDayWarning)) {
                $message .= ' ' . $halfDayWarning;
            }

            return redirect()->route('absensi.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Absensi $absensi)
    {
        $absensi->load(['karyawan', 'jabatan', 'lokasi', 'kandang', 'bibit']);
        return view('absensi.show', compact('absensi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absensi $absensi)
    {
        $karyawans = Karyawan::where('status_aktif', true)->with('jabatan')->get();
        $lokasis = Lokasi::all();
        $kandangs = Kandang::all();
        // Hanya tampilkan bibit dari kandang yang punya bibit (1 kandang = 1 bibit)
        $bibits = Bibit::with('kandang')->get();

        return view('absensi.edit', compact('absensi', 'karyawans', 'lokasis', 'kandangs', 'bibits'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAbsensiRequest $request, Absensi $absensi)
    {
        try {
            // Auto-fill jabatan from karyawan
            $karyawan = Karyawan::findOrFail($request->karyawan_id);
            $request->merge([
                'jabatan_id' => $karyawan->jabatan_id,
            ]);

            // Auto-fill lokasi and kandang from bibit (bibit is required now)
            $bibit = Bibit::findOrFail($request->bibit_id);
            $request->merge([
                'lokasi_id' => $bibit->lokasi_id,
                'kandang_id' => $bibit->kandang_id,
            ]);

            $absensi->update($request->validated());

            return redirect()->route('absensi.index')
                ->with('success', 'Absensi berhasil diperbarui.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                // Unique constraint violation - check if it's the unique constraint on karyawan_id + tanggal
                if (str_contains($e->getMessage(), 'absensis_karyawan_id_tanggal_unique')) {
                    $karyawan = Karyawan::find($request->karyawan_id);
                    $karyawanNama = $karyawan ? $karyawan->nama : 'karyawan ini';
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "Absensi untuk {$karyawanNama} pada tanggal " . date('d/m/Y', strtotime($request->tanggal)) . " sudah ada. Silakan pilih tanggal lain.");
                }
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Data yang dimasukkan sudah ada. Silakan periksa kembali.');
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absensi $absensi)
    {
        try {
            $absensi->delete();

            return redirect()->route('absensi.index')
                ->with('success', 'Absensi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('absensi.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Get kandangs by lokasi (AJAX)
     */
    public function getKandangsByLokasi(Request $request)
    {
        $kandangs = Kandang::where('lokasi_id', $request->lokasi_id)->get();
        return response()->json($kandangs);
    }

    /**
     * Get bibit by kandang (AJAX) - 1 kandang = 1 bibit
     */
    public function getBibitsByKandang(Request $request)
    {
        $kandang = Kandang::with('bibit')->find($request->kandang_id);
        if ($kandang && $kandang->bibit) {
            return response()->json([$kandang->bibit]);
        }
        return response()->json([]);
    }

    /**
     * Auto-fill from bibit (AJAX)
     */
    public function autoFillFromBibit(Request $request)
    {
        $bibit = Bibit::with(['lokasi', 'kandang'])->find($request->bibit_id);
        
        if ($bibit) {
            return response()->json([
                'lokasi_id' => $bibit->lokasi_id,
                'kandang_id' => $bibit->kandang_id,
            ]);
        }

        return response()->json([], 404);
    }

    /**
     * Export absensi to XLSX
     */
    public function export(Request $request)
    {
        $query = Absensi::with(['karyawan', 'jabatan', 'lokasi', 'kandang', 'bibit']);

        // Apply same filters as index
        if ($request->filled('karyawan_id')) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        if ($request->filled('jabatan_id')) {
            $query->where('jabatan_id', $request->jabatan_id);
        }

        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        if ($request->filled('kandang_id')) {
            $query->where('kandang_id', $request->kandang_id);
        }

        if ($request->filled('bibit_id')) {
            $query->where('bibit_id', $request->bibit_id);
        }

        if ($request->filled('tipe_absen')) {
            $query->where('tipe_absen', $request->tipe_absen);
        }

        if ($request->filled('start_date')) {
            $query->where('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('tanggal', '<=', $request->end_date);
        }
        
        // Jika user tidak set start_date dan end_date, default ke hari ini
        if (!$request->filled('start_date') && !$request->filled('end_date')) {
            $query->whereDate('tanggal', today());
        }

        if ($request->filled('tanggal_bibit')) {
            $query->whereHas('bibit', function($q) use ($request) {
                $q->whereDate('tanggal_masuk', $request->tanggal_bibit);
            });
        }

        $absensis = $query->latest('tanggal')->get();

        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Absensi');

        // Set header
        $headers = [
            'No',
            'Tanggal',
            'Nama Karyawan',
            'Jabatan',
            'Jenis Bibit',
            'Tanggal Bibit',
            'Lokasi',
            'Kandang',
            'Tipe Absen',
        ];

        // Style for header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        // Write headers
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $sheet->getStyle($column . '1')->applyFromArray($headerStyle);
            $column++;
        }

        // Write data
        $row = 2;
        $no = 1;
        foreach ($absensis as $absensi) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $absensi->tanggal->format('d/m/y'));
            $sheet->setCellValue('C' . $row, $absensi->karyawan->nama);
            $sheet->setCellValue('D' . $row, $absensi->jabatan->nama_jabatan);
            $sheet->setCellValue('E' . $row, $absensi->bibit ? $absensi->bibit->jenis_bibit : '-');
            $sheet->setCellValue('F' . $row, $absensi->bibit && $absensi->bibit->tanggal_masuk ? $absensi->bibit->tanggal_masuk->format('d/m/y') : '-');
            $sheet->setCellValue('G' . $row, $absensi->lokasi->nama_lokasi);
            $sheet->setCellValue('H' . $row, $absensi->kandang->nama_kandang);
            $sheet->setCellValue('I' . $row, $absensi->tipe_absen == 'full' ? 'Full Day' : 'Half Day');
            
            // Add borders to data rows
            $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);
            
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set header row height
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Create writer
        $filename = 'laporan_absensi_' . date('Y-m-d_His') . '.xlsx';
        
        // Save to temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Check existing half day records for given karyawan and date (AJAX)
     */
    public function checkExistingHalfDay(Request $request)
    {
        $request->validate([
            'karyawan_ids' => ['required', 'array'],
            'karyawan_ids.*' => ['required', 'exists:karyawans,id'],
            'tanggal' => ['required', 'date'],
        ]);

        $existingHalfDay = Absensi::whereIn('karyawan_id', $request->karyawan_ids)
            ->where('tanggal', $request->tanggal)
            ->where('tipe_absen', 'half')
            ->with(['karyawan', 'kandang'])
            ->get();

        $counts = $existingHalfDay
            ->groupBy('karyawan_id')
            ->map(fn ($items) => $items->count());

        return response()->json([
            'existing' => $existingHalfDay,
            'counts' => $counts,
            'limit' => 2,
        ]);
    }
}
