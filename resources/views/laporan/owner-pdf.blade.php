<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Owner</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        h1 { font-size: 18px; margin: 0 0 8px 0; }
        .meta { margin: 0 0 12px 0; }
        .meta-row { margin: 2px 0; }
        .label { display: inline-block; width: 110px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 6px 8px; }
        th { background: #f3f4f6; text-align: left; }
        .right { text-align: right; }
        .center { text-align: center; }
        tfoot td { font-weight: 700; background: #eef2ff; }
    </style>
</head>
<body>
    <h1>Laporan Owner</h1>
    <div class="meta">
        <div class="meta-row"><span class="label">Filter Aktif</span></div>
        <div class="meta-row"><span class="label">Jabatan</span> {{ $filterSummary['jabatan'] }}</div>
        <div class="meta-row"><span class="label">Nama Pegawai</span> {{ $filterSummary['nama_pegawai'] }}</div>
        <div class="meta-row"><span class="label">Lokasi</span> {{ $filterSummary['lokasi'] }}</div>
        <div class="meta-row"><span class="label">Kandang</span> {{ $filterSummary['kandang'] }}</div>
        <div class="meta-row"><span class="label">Bibit</span> {{ $filterSummary['bibit'] }}</div>
        <div class="meta-row"><span class="label">Periode</span> {{ $filterSummary['rentang_tanggal'] }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Jabatan</th>
                <th class="right">Gaji Pokok</th>
                <th class="center">Total Hari Full</th>
                <th class="center">Total Hari Half</th>
                <th class="right">Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @forelse($report['data'] as $item)
                <tr>
                    <td>{{ $item['nama'] }}</td>
                    <td>{{ $item['jabatan'] }}</td>
                    <td class="right">Rp {{ number_format($item['gaji_pokok'], 0, ',', '.') }}</td>
                    <td class="center">{{ $item['total_hari_full'] }}</td>
                    <td class="center">{{ $item['total_hari_half'] }}</td>
                    <td class="right">Rp {{ number_format($item['total_gaji'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="right">Grand Total</td>
                <td class="right">Rp {{ number_format($report['total_biaya'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
