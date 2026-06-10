<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recap Bibit</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        h1 { font-size: 18px; margin: 0 0 8px 0; }
        .meta { margin: 0 0 12px 0; }
        .meta-row { margin: 2px 0; }
        .label { display: inline-block; width: 110px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 6px 8px; }
        th { background: #f3f4f6; text-align: left; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <h1>Recap Bibit</h1>
    <div class="meta">
        <div class="meta-row"><span class="label">Filter Aktif</span></div>
        <div class="meta-row"><span class="label">Lokasi</span> {{ $filterSummary['lokasi'] }}</div>
        <div class="meta-row"><span class="label">Kandang</span> {{ $filterSummary['kandang'] }}</div>
        <div class="meta-row"><span class="label">Jenis Bibit</span> {{ $filterSummary['jenis_bibit'] }}</div>
        <div class="meta-row"><span class="label">Tanggal Masuk</span> {{ $filterSummary['tanggal_masuk'] }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Jenis Bibit</th>
                <th>Lokasi</th>
                <th>Kandang</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Selesai</th>
                <th class="center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bibits as $bibit)
                <tr>
                    <td>{{ $bibit->jenis_bibit }}</td>
                    <td>{{ $bibit->lokasi->nama_lokasi ?? '-' }}</td>
                    <td>{{ $bibit->kandang->nama_kandang ?? '-' }}</td>
                    <td>{{ $bibit->tanggal_masuk ? \Carbon\Carbon::parse($bibit->tanggal_masuk)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $bibit->tanggal_selesai ? \Carbon\Carbon::parse($bibit->tanggal_selesai)->format('d/m/Y') : '-' }}</td>
                    <td class="center">{{ $bibit->status == 'aktif' ? 'Aktif' : 'Selesai' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
