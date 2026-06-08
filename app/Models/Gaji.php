<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gaji extends Model
{
    protected $fillable = [
        'karyawan_id',
        'gaji_pokok',
        'berlaku_mulai',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'berlaku_mulai' => 'date',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
