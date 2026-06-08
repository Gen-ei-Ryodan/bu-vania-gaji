<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lokasi extends Model
{
    protected $fillable = ['nama_lokasi'];

    public function kandangs(): HasMany
    {
        return $this->hasMany(Kandang::class);
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

        static::deleting(function ($lokasi) {
            if ($lokasi->absensis()->exists()) {
                throw new \Exception('Data tidak dapat dihapus karena sudah memiliki transaksi absensi.');
            }
            if ($lokasi->kandangs()->exists() || $lokasi->bibits()->exists()) {
                 throw new \Exception('Data tidak dapat dihapus karena masih memiliki Kandang atau Bibit.');
            }
        });
    }
}
