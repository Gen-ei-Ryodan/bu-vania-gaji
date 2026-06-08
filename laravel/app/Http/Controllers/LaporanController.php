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
     * Display owner report
     */
    public function owner(Request $request)
    {
        if (! auth()->user()->hasRole('Owner')) {
            abort(403, 'Unauthorized access.');
        }

        $filters = $request->only(['start_date', 'end_date', 'jabatan_id', 'nama_pegawai', 'lokasi_id', 'kandang_id', 'bibit_id']);

        $report = $this->salaryService->calculateSalaryReport($filters);

        $jabatans = Jabatan::all();
        $lokasis = Lokasi::all();
        $kandangs = Kandang::all();
        $bibits = Bibit::with('kandang')->latest('tanggal_masuk')->get();
        $filterSummary = $this->buildFilterSummary($filters, $report);

        return view('laporan.owner', compact('report', 'jabatans', 'lokasis', 'kandangs', 'bibits', 'filterSummary'));
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
        $bibits = Bibit::with('kandang')->latest('tanggal_masuk')->get();
        $filterSummary = $this->buildFilterSummary($filters, $report);

        return view('laporan.admin', compact('report', 'jabatans', 'lokasis', 'kandangs', 'bibits', 'filterSummary'));
    }

    /**
     * Export owner report to XLSX
     */
    public function exportOwner(Request $request)
    {
        if (! auth()->user()->hasRole('Owner')) {
            abort(403, 'Unauthorized access.');
        }

        $filters = $request->only(['start_date', 'end_date', 'jabatan_id', 'nama_pegawai', 'lokasi_id', 'kandang_id', 'bibit_id']);

        $report = $this->salaryService->calculateSalaryReport($filters);
        $filterSummary = $this->buildFilterSummary($filters, $report);

        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Owner');

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
        $headers = ['Nama', 'Jabatan', 'Gaji Pokok', 'Total Hari Full', 'Total Hari Half', 'Total Biaya'];
        $headerRow = $row;
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column.$headerRow, $header);
            $sheet->getStyle($column.$headerRow)->applyFromArray($headerStyle);
            $column++;
        }

        // Write data
        $row = $headerRow + 1;
        foreach ($report['data'] as $item) {
            $sheet->setCellValue('A'.$row, $item['nama']);
            $sheet->setCellValue('B'.$row, $item['jabatan']);
            $sheet->setCellValue('C'.$row, number_format($item['gaji_pokok'], 0, ',', '.'));
            $sheet->setCellValue('D'.$row, $item['total_hari_full']);
            $sheet->setCellValue('E'.$row, $item['total_hari_half']);
            $sheet->setCellValue('F'.$row, number_format($item['total_gaji'], 0, ',', '.'));

            // Add borders
            $sheet->getStyle('A'.$row.':F'.$row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);

            $row++;
        }

        // Write grand total
        $sheet->setCellValue('A'.$row, 'Grand Total');
        $sheet->setCellValue('F'.$row, number_format($report['total_biaya'], 0, ',', '.'));
        $sheet->getStyle('A'.$row.':F'.$row)->applyFromArray($totalStyle);
        $sheet->mergeCells('A'.$row.':E'.$row);
        $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Auto size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set row heights
        $sheet->getRowDimension($headerRow)->setRowHeight(25);

        // Create writer
        $filename = 'laporan_owner_'.date('Y-m-d_His').'.xlsx';

        // Save to temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
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

    public function exportOwnerPdf(Request $request)
    {
        if (! auth()->user()->hasRole('Owner')) {
            abort(403, 'Unauthorized access.');
        }

        $filters = $request->only(['start_date', 'end_date', 'jabatan_id', 'nama_pegawai', 'lokasi_id', 'kandang_id', 'bibit_id']);
        $report = $this->salaryService->calculateSalaryReport($filters);
        $filterSummary = $this->buildFilterSummary($filters, $report);

        $pdf = Pdf::loadView('laporan.owner-pdf', [
            'report' => $report,
            'filterSummary' => $filterSummary,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan_owner_'.date('Y-m-d_His').'.pdf');
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
}
