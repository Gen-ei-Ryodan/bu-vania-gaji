<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kandang extends Model
{
    protected $fillable = ['lokasi_id', 'nama_kandang'];

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function bibit(): HasOne
    {
        return $this->hasOne(Bibit::class)
            ->where('status', 'aktif')
            ->orderByDesc('tanggal_masuk');
    }

    public function bibits(): HasMany
    {
        return $this->hasMany(Bibit::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($kandang) {
            if ($kandang->absensis()->exists()) {
                throw new \Exception('Data tidak dapat dihapus karena sudah memiliki transaksi absensi.');
            }
            if ($kandang->bibits()->exists()) {
                throw new \Exception('Data tidak dapat dihapus karena masih digunakan oleh Bibit.');
            }
        });
    }
}
