<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    protected $fillable = ['nama_jabatan'];

    public function karyawans(): HasMany
    {
        return $this->hasMany(Karyawan::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($jabatan) {
            if ($jabatan->absensis()->exists()) {
                throw new \Exception('Data tidak dapat dihapus karena sudah memiliki transaksi absensi.');
            }
            if ($jabatan->karyawans()->exists()) {
                 throw new \Exception('Data tidak dapat dihapus karena masih digunakan oleh Karyawan.');
            }
        });
    }
}
