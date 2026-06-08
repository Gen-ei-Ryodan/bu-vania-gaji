<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bibit extends Model
{
    protected $fillable = [
        'lokasi_id',
        'kandang_id',
        'jenis_bibit',
        'tanggal_masuk',
        'tanggal_selesai',
        'status',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function kandang(): BelongsTo
    {
        return $this->belongsTo(Kandang::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($bibit) {
            // Jika status bibit 'non-aktif', izinkan hapus (akan cascade delete absensi)
            if ($bibit->status !== 'non-aktif' && $bibit->absensis()->exists()) {
                throw new \Exception('Data tidak dapat dihapus karena sudah memiliki transaksi absensi. Ubah status menjadi Non Aktif jika ingin menghapus.');
            }
        });
    }
}
