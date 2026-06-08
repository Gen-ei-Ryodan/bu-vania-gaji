<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Admin</title>
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
        tfoot td { font-weight: 700; background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Laporan Admin</h1>
    <div class="meta">
        <div class="meta-row"><span class="label">Filter Aktif</span></div>
        <div class="meta-row"><span class="label">Jabatan</span> {{ $filterSummary['jabatan'] }}</div>
        <div class="meta-row"><span class="label">Nama Pegawai</span> {{ $filterSummary['nama_pegawai'] }}</div>
        <div class="meta-row"><span class="label">Lokasi</span> {{ $filterSummary['lokasi'] }}</div>
        <div class="meta-row"><span class="label">Kandang</span> {{ $filterSummary['kandang'] }}</div>
        <div class="meta-row"><span class="label">Bibit</span> {{ $filterSummary['bibit'] }}</div>
        <div class="meta-row"><span class="label">Periode</span> {{ $filterSummary['rentang_tanggal'] }}</div>
    </div>

    @php
        $grandTotalFull = 0;
        $grandTotalHalf = 0;
    @endphp

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Jabatan</th>
                <th class="center">Hari Full</th>
                <th class="center">Hari Half</th>
            </tr>
        </thead>
        <tbody>
            @forelse($report['data'] as $item)
                @php
                    $grandTotalFull += $item['total_hari_full'];
                    $grandTotalHalf += $item['total_hari_half'];
                @endphp
                <tr>
                    <td>{{ $item['nama'] }}</td>
                    <td>{{ $item['jabatan'] }}</td>
                    <td class="center">{{ $item['total_hari_full'] }}</td>
                    <td class="center">{{ $item['total_hari_half'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="right">Grand Total Hari</td>
                <td class="center">{{ $grandTotalFull }}</td>
                <td class="center">{{ $grandTotalHalf }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
