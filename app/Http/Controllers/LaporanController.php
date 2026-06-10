<?php

namespace App\Http\Controllers;

use App\Models\Bibit;
use App\Models\Jabatan;
use App\Models\Kandang;
use App\Models\Lokasi;
use App\Services\SalaryService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends Controller
{
    protected $salaryService;

    public function __construct(SalaryService $salaryService)
    {
        $this->salaryService = $salaryService;
    }

    private function buildFilterSummary(array $filters, array $report): array
    {
        $jabatanName = 'Semua Jabatan';
        if (! empty($filters['jabatan_id'])) {
            $jabatan = Jabatan::find($filters['jabatan_id']);
            $jabatanName = $jabatan ? $jabatan->nama_jabatan : $jabatanName;
        }

        $namaPegawai = ! empty($filters['nama_pegawai']) ? $filters['nama_pegawai'] : 'Semua Pegawai';

        $lokasiName = 'Semua Lokasi';
        if (! empty($filters['lokasi_id'])) {
            $lokasi = Lokasi::find($filters['lokasi_id']);
            $lokasiName = $lokasi ? $lokasi->nama_lokasi : $lokasiName;
        }

        $kandangName = 'Semua Kandang';
        if (! empty($filters['kandang_id'])) {
            $kandang = Kandang::find($filters['kandang_id']);
            $kandangName = $kandang ? $kandang->nama_kandang : $kandangName;
        }

        $bibitName = 'Semua Bibit';
        if (! empty($filters['bibit_id'])) {
            $bibit = Bibit::with('kandang')->find($filters['bibit_id']);
            $bibitName = $bibit ? ($bibit->jenis_bibit.' - '.$bibit->kandang?->nama_kandang) : $bibitName;
        }

        $rentangTanggal = date('d/m/Y', strtotime($report['start_date'])).' s/d '.date('d/m/Y', strtotime($report['end_date']));

        return [
            'jabatan' => $jabatanName,
            'nama_pegawai' => $namaPegawai,
            'lokasi' => $lokasiName,
            'kandang' => $kandangName,
            'bibit' => $bibitName,
            'rentang_tanggal' => $rentangTanggal,
        ];
    }

    private function maskGajiPokokUntukAdmin(array &$report): void
    {
        if (! auth()->user()->hasRole('Admin')) {
            return;
        }

        $highPositions = ['Mandor', 'Sekretaris', 'Admin'];
        foreach ($report['data'] as &$item) {
            if (in_array($item['jabatan'], $highPositions)) {
                $item['gaji_pokok'] = 0;
            }
        }
    }

    /**
     * Display admin report
     */
    public function admin(Request $request)
    {
        if (! auth()->user()->can('view-any-laporan')) {
            abort(403, 'Unauthorized access.');
        }

        $filters = $request->only(['start_date', 'end_date', 'jabatan_id', 'nama_pegawai', 'lokasi_id', 'kandang_id', 'bibit_id']);

        $report = $this->salaryService->calculateSalaryReport($filters);

        $this->maskGajiPokokUntukAdmin($report);

        $jabatans = Jabatan::all();
        $lokasis = Lokasi::all();
        $kandangs = Kandang::all();
        $bibits = Bibit::with('kandang')->orderBy('status')->latest('tanggal_masuk')->get();
        $filterSummary = $this->buildFilterSummary($filters, $report);

        return view('laporan.admin', compact('report', 'jabatans', 'lokasis', 'kandangs', 'bibits', 'filterSummary'));
    }
    /**
     * Export admin report to XLSX
     */
    public function exportAdmin(Request $request)
    {
        if (! auth()->user()->can('view-any-laporan')) {
            abort(403, 'Unauthorized access.');
        }

        $filters = $request->only(['start_date', 'end_date', 'jabatan_id', 'nama_pegawai', 'lokasi_id', 'kandang_id', 'bibit_id']);
        $report = $this->salaryService->calculateSalaryReport($filters);

        $this->maskGajiPokokUntukAdmin($report);
        $filterSummary = $this->buildFilterSummary($filters, $report);

        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Admin');

        // Header style
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

        // Title style
        $titleStyle = [
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
        ];

        // Summary style
        $summaryStyle = [
            'font' => [
                'bold' => true,
            ],
        ];

        // Total style
        $totalStyle = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E7E6E6'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        // Determine headers based on role
        $headers = ['Nama', 'Jabatan', 'Hari Full', 'Hari Half'];
        $lastColumn = 'D';

        // Write summary
        $row = 1;
        $sheet->setCellValue('A'.$row, 'Jabatan:');
        $sheet->setCellValue('B'.$row, $filterSummary['jabatan']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;

        $sheet->setCellValue('A'.$row, 'Nama Pegawai:');
        $sheet->setCellValue('B'.$row, $filterSummary['nama_pegawai']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;

        $sheet->setCellValue('A'.$row, 'Lokasi:');
        $sheet->setCellValue('B'.$row, $filterSummary['lokasi']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;

        $sheet->setCellValue('A'.$row, 'Kandang:');
        $sheet->setCellValue('B'.$row, $filterSummary['kandang']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;

        $sheet->setCellValue('A'.$row, 'Bibit:');
        $sheet->setCellValue('B'.$row, $filterSummary['bibit']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;

        $sheet->setCellValue('A'.$row, 'Rentang Tanggal:');
        $sheet->setCellValue('B'.$row, $filterSummary['rentang_tanggal']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;

        // Empty row before headers
        $row++;

        // Write headers
        $headerRow = $row;
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column.$headerRow, $header);
            $sheet->getStyle($column.$headerRow)->applyFromArray($headerStyle);
            $column++;
        }

        // Write data
        $row = $headerRow + 1;
        $grandTotalFull = 0;
        $grandTotalHalf = 0;

        foreach ($report['data'] as $item) {
            $sheet->setCellValue('A'.$row, $item['nama']);
            $sheet->setCellValue('B'.$row, $item['jabatan']);
            $sheet->setCellValue('C'.$row, $item['total_hari_full']);
            $sheet->setCellValue('D'.$row, $item['total_hari_half']);

            $grandTotalFull += $item['total_hari_full'];
            $grandTotalHalf += $item['total_hari_half'];

            // Add borders
            $sheet->getStyle('A'.$row.':'.$lastColumn.$row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);

            $row++;
        }

        // Write grand total
        $sheet->setCellValue('A'.$row, 'Grand Total Hari');
        $sheet->setCellValue('C'.$row, $grandTotalFull);
        $sheet->setCellValue('D'.$row, $grandTotalHalf);

        // For Admin, show Grand Total in a separate row or below
        $sheet->getStyle('A'.$row.':'.$lastColumn.$row)->applyFromArray($totalStyle);
        $sheet->mergeCells('A'.$row.':B'.$row);
        $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Auto size columns
        $columns = ['A', 'B', 'C', 'D'];
        foreach ($columns as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set row heights
        $sheet->getRowDimension($headerRow)->setRowHeight(25);

        // Create writer
        $filename = 'laporan_admin_'.date('Y-m-d_His').'.xlsx';

        // Save to temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function exportAdminPdf(Request $request)
    {
        if (! auth()->user()->can('view-any-laporan')) {
            abort(403, 'Unauthorized access.');
        }

        $filters = $request->only(['start_date', 'end_date', 'jabatan_id', 'nama_pegawai', 'lokasi_id', 'kandang_id', 'bibit_id']);
        $report = $this->salaryService->calculateSalaryReport($filters);
        $this->maskGajiPokokUntukAdmin($report);
        $filterSummary = $this->buildFilterSummary($filters, $report);

        $pdf = Pdf::loadView('laporan.admin-pdf', [
            'report' => $report,
            'filterSummary' => $filterSummary,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan_admin_'.date('Y-m-d_His').'.pdf');
    }

    // =========================
    // LAPORAN PER BIBIT
    // =========================

    public function perBibit(Request $request)
    {
        if (! auth()->user()->hasRole('Owner')) {
            abort(403, 'Unauthorized access.');
        }

        $filters = $request->only(['jabatan_id', 'lokasi_id', 'kandang_id', 'bibit_id', 'nama_pegawai']);
        $report = $this->salaryService->calculatePerBibitReport($filters);
        $this->maskGajiPokokUntukAdmin($report);

        $jabatans = Jabatan::all();
        $lokasis = Lokasi::all();
        $kandangs = Kandang::all();
        $bibits = Bibit::with('kandang')->orderBy('status')->latest('tanggal_masuk')->get();
        $filterSummary = $this->buildPerBibitFilterSummary($filters, $report);

        return view('laporan.per-bibit', compact('report', 'jabatans', 'lokasis', 'kandangs', 'bibits', 'filterSummary'));
    }

    public function exportPerBibit(Request $request)
    {
        if (! auth()->user()->hasRole('Owner')) {
            abort(403, 'Unauthorized access.');
        }

        $filters = $request->only(['jabatan_id', 'lokasi_id', 'kandang_id', 'bibit_id', 'nama_pegawai']);
        $report = $this->salaryService->calculatePerBibitReport($filters);
        $this->maskGajiPokokUntukAdmin($report);
        $filterSummary = $this->buildPerBibitFilterSummary($filters, $report);

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Per Bibit');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];
        $summaryStyle = ['font' => ['bold' => true]];
        $totalStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E7E6E6']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];

        $row = 1;
        $sheet->setCellValue('A'.$row, 'Jabatan:');
        $sheet->setCellValue('B'.$row, $filterSummary['jabatan']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;
        $sheet->setCellValue('A'.$row, 'Nama Pegawai:');
        $sheet->setCellValue('B'.$row, $filterSummary['nama_pegawai']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;
        $sheet->setCellValue('A'.$row, 'Lokasi:');
        $sheet->setCellValue('B'.$row, $filterSummary['lokasi']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;
        $sheet->setCellValue('A'.$row, 'Kandang:');
        $sheet->setCellValue('B'.$row, $filterSummary['kandang']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;
        $sheet->setCellValue('A'.$row, 'Bibit:');
        $sheet->setCellValue('B'.$row, $filterSummary['bibit']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;
        $sheet->setCellValue('A'.$row, 'Dari Tanggal:');
        $sheet->setCellValue('B'.$row, $filterSummary['tanggal_mulai']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;
        $row++;

        $headers = ['Nama', 'Jabatan', 'Gaji Pokok', 'Total Hari Full', 'Total Hari Half', 'Total Biaya'];
        $headerRow = $row;
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column.$headerRow, $header);
            $sheet->getStyle($column.$headerRow)->applyFromArray($headerStyle);
            $column++;
        }

        $row = $headerRow + 1;
        foreach ($report['data'] as $item) {
            $sheet->setCellValue('A'.$row, $item['nama']);
            $sheet->setCellValue('B'.$row, $item['jabatan']);
            $sheet->setCellValue('C'.$row, number_format($item['gaji_pokok'], 0, ',', '.'));
            $sheet->setCellValue('D'.$row, $item['total_hari_full']);
            $sheet->setCellValue('E'.$row, $item['total_hari_half']);
            $sheet->setCellValue('F'.$row, number_format($item['total_gaji'], 0, ',', '.'));
            $sheet->getStyle('A'.$row.':F'.$row)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);
            $row++;
        }

        $sheet->setCellValue('A'.$row, 'Grand Total');
        $sheet->setCellValue('F'.$row, number_format($report['total_biaya'], 0, ',', '.'));
        $sheet->getStyle('A'.$row.':F'.$row)->applyFromArray($totalStyle);
        $sheet->mergeCells('A'.$row.':E'.$row);
        $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getRowDimension($headerRow)->setRowHeight(25);

        $filename = 'laporan_per_bibit_'.date('Y-m-d_His').'.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // ===========================
    // LAPORAN PER LOKASI
    // ===========================

    public function perLokasi(Request $request)
    {
        if (! auth()->user()->hasRole('Owner')) {
            abort(403, 'Unauthorized access.');
        }

        $filters = $request->only(['start_date', 'end_date', 'jabatan_id', 'lokasi_id', 'kandang_id', 'nama_pegawai']);
        $report = $this->salaryService->calculatePerLokasiReport($filters);
        $this->maskGajiPokokUntukAdmin($report);

        $jabatans = Jabatan::all();
        $lokasis = Lokasi::all();
        $kandangs = Kandang::all();
        $filterSummary = $this->buildPerLokasiFilterSummary($filters, $report);

        return view('laporan.per-lokasi', compact('report', 'jabatans', 'lokasis', 'kandangs', 'filterSummary'));
    }

    public function exportPerLokasi(Request $request)
    {
        if (! auth()->user()->hasRole('Owner')) {
            abort(403, 'Unauthorized access.');
        }

        $filters = $request->only(['start_date', 'end_date', 'jabatan_id', 'lokasi_id', 'kandang_id', 'nama_pegawai']);
        $report = $this->salaryService->calculatePerLokasiReport($filters);
        $this->maskGajiPokokUntukAdmin($report);
        $filterSummary = $this->buildPerLokasiFilterSummary($filters, $report);

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Per Lokasi');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];
        $summaryStyle = ['font' => ['bold' => true]];
        $totalStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E7E6E6']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];

        $row = 1;
        $sheet->setCellValue('A'.$row, 'Jabatan:');
        $sheet->setCellValue('B'.$row, $filterSummary['jabatan']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;
        $sheet->setCellValue('A'.$row, 'Nama Pegawai:');
        $sheet->setCellValue('B'.$row, $filterSummary['nama_pegawai']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;
        $sheet->setCellValue('A'.$row, 'Lokasi:');
        $sheet->setCellValue('B'.$row, $filterSummary['lokasi']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;
        $sheet->setCellValue('A'.$row, 'Kandang:');
        $sheet->setCellValue('B'.$row, $filterSummary['kandang']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;
        $sheet->setCellValue('A'.$row, 'Rentang Tanggal:');
        $sheet->setCellValue('B'.$row, $filterSummary['rentang_tanggal']);
        $sheet->getStyle('A'.$row)->applyFromArray($summaryStyle);
        $row++;
        $row++;

        $headers = ['Nama', 'Jabatan', 'Gaji Pokok', 'Total Hari Full', 'Total Hari Half', 'Total Biaya'];
        $headerRow = $row;
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column.$headerRow, $header);
            $sheet->getStyle($column.$headerRow)->applyFromArray($headerStyle);
            $column++;
        }

        $row = $headerRow + 1;
        foreach ($report['data'] as $item) {
            $sheet->setCellValue('A'.$row, $item['nama']);
            $sheet->setCellValue('B'.$row, $item['jabatan']);
            $sheet->setCellValue('C'.$row, number_format($item['gaji_pokok'], 0, ',', '.'));
            $sheet->setCellValue('D'.$row, $item['total_hari_full']);
            $sheet->setCellValue('E'.$row, $item['total_hari_half']);
            $sheet->setCellValue('F'.$row, number_format($item['total_gaji'], 0, ',', '.'));
            $sheet->getStyle('A'.$row.':F'.$row)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);
            $row++;
        }

        $sheet->setCellValue('A'.$row, 'Grand Total');
        $sheet->setCellValue('F'.$row, number_format($report['total_biaya'], 0, ',', '.'));
        $sheet->getStyle('A'.$row.':F'.$row)->applyFromArray($totalStyle);
        $sheet->mergeCells('A'.$row.':E'.$row);
        $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getRowDimension($headerRow)->setRowHeight(25);

        $filename = 'laporan_per_lokasi_'.date('Y-m-d_His').'.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // =========================
    // RECAP BIBIT
    // =========================

    public function recapBibit(Request $request)
    {
        if (! auth()->user()->hasRole('Owner')) {
            abort(403, 'Unauthorized access.');
        }

        $query = Bibit::with(['lokasi', 'kandang']);

        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        if ($request->filled('kandang_id')) {
            $query->where('kandang_id', $request->kandang_id);
        }

        if ($request->filled('jenis_bibit')) {
            $query->where('jenis_bibit', $request->jenis_bibit);
        }

        if ($request->filled('tanggal_masuk_start')) {
            $query->where('tanggal_masuk', '>=', $request->tanggal_masuk_start);
        }

        if ($request->filled('tanggal_masuk_end')) {
            $query->where('tanggal_masuk', '<=', $request->tanggal_masuk_end);
        }

        $bibits = $query->orderByDesc('tanggal_masuk')->get();

        $lokasis = Lokasi::all();
        $kandangs = Kandang::all();
        $jenisBibits = Bibit::select('jenis_bibit')->distinct()->pluck('jenis_bibit');

        $filterSummary = [
            'lokasi' => $request->filled('lokasi_id') ? Lokasi::find($request->lokasi_id)->nama_lokasi : 'Semua Lokasi',
            'kandang' => $request->filled('kandang_id') ? Kandang::find($request->kandang_id)->nama_kandang : 'Semua Kandang',
            'jenis_bibit' => $request->filled('jenis_bibit') ? $request->jenis_bibit : 'Semua Jenis',
            'tanggal_masuk' => ($request->filled('tanggal_masuk_start') || $request->filled('tanggal_masuk_end'))
                ? ($request->tanggal_masuk_start ?? '...') . ' s/d ' . ($request->tanggal_masuk_end ?? '...')
                : 'Semua Tanggal',
        ];

        return view('laporan.recap-bibit', compact('bibits', 'lokasis', 'kandangs', 'jenisBibits', 'filterSummary'));
    }

    // ===================================
    // FILTER SUMMARY BUILDERS
    // ===================================

    private function buildPerBibitFilterSummary(array $filters, array $report): array
    {
        $jabatan = Jabatan::find($filters['jabatan_id'] ?? 0);
        $jabatanName = $jabatan ? $jabatan->nama_jabatan : 'Semua Jabatan';
        $namaPegawai = ! empty($filters['nama_pegawai']) ? $filters['nama_pegawai'] : 'Semua Pegawai';

        $lokasi = ! empty($filters['lokasi_id']) ? Lokasi::find($filters['lokasi_id']) : null;
        $lokasiName = $lokasi ? $lokasi->nama_lokasi : 'Semua Lokasi';

        $kandang = ! empty($filters['kandang_id']) ? Kandang::find($filters['kandang_id']) : null;
        $kandangName = $kandang ? $kandang->nama_kandang : 'Semua Kandang';

        $bibitName = 'Semua Bibit';
        if (! empty($report['bibit'])) {
            $bibitName = $report['bibit']->jenis_bibit . ' - ' . $report['bibit']->kandang?->nama_kandang;
        } elseif (! empty($filters['bibit_id'])) {
            $bibit = Bibit::with('kandang')->find($filters['bibit_id']);
            $bibitName = $bibit ? ($bibit->jenis_bibit . ' - ' . $bibit->kandang?->nama_kandang) : $bibitName;
        }

        $tanggalMulai = date('d/m/Y', strtotime($report['start_date']));

        return [
            'jabatan' => $jabatanName,
            'nama_pegawai' => $namaPegawai,
            'lokasi' => $lokasiName,
            'kandang' => $kandangName,
            'bibit' => $bibitName,
            'tanggal_mulai' => $tanggalMulai,
        ];
    }

    private function buildPerLokasiFilterSummary(array $filters, array $report): array
    {
        $jabatan = Jabatan::find($filters['jabatan_id'] ?? 0);
        $jabatanName = $jabatan ? $jabatan->nama_jabatan : 'Semua Jabatan';
        $namaPegawai = ! empty($filters['nama_pegawai']) ? $filters['nama_pegawai'] : 'Semua Pegawai';

        $lokasi = ! empty($filters['lokasi_id']) ? Lokasi::find($filters['lokasi_id']) : null;
        $lokasiName = $lokasi ? $lokasi->nama_lokasi : 'Semua Lokasi';

        $kandang = ! empty($filters['kandang_id']) ? Kandang::find($filters['kandang_id']) : null;
        $kandangName = $kandang ? $kandang->nama_kandang : 'Semua Kandang';

        $rentangTanggal = date('d/m/Y', strtotime($report['start_date'])) . ' s/d ' . date('d/m/Y', strtotime($report['end_date']));

        return [
            'jabatan' => $jabatanName,
            'nama_pegawai' => $namaPegawai,
            'lokasi' => $lokasiName,
            'kandang' => $kandangName,
            'rentang_tanggal' => $rentangTanggal,
        ];
    }
}
