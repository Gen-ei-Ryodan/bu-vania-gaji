<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $fillable = [
        'karyawan_id',
        'jabatan_id',
        'lokasi_id',
        'kandang_id',
        'bibit_id',
        'tipe_absen',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function kandang(): BelongsTo
    {
        return $this->belongsTo(Kandang::class);
    }

    public function bibit(): BelongsTo
    {
        return $this->belongsTo(Bibit::class);
    }
}
