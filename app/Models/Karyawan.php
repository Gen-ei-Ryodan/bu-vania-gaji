<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Karyawan extends Model
{
    protected $fillable = ['nama', 'jabatan_id', 'gaji_pokok', 'status_aktif'];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'status_aktif' => 'boolean',
    ];

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function gajis(): HasMany
    {
        return $this->hasMany(Gaji::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function getGajiAktifAttribute()
    {
        return $this->gajis()
            ->where('berlaku_mulai', '<=', now())
            ->latest('berlaku_mulai')
            ->first();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($karyawan) {
            if ($karyawan->absensis()->exists()) {
                throw new \Exception('Data tidak dapat dihapus karena sudah memiliki transaksi absensi.');
            }
        });
    }
}
