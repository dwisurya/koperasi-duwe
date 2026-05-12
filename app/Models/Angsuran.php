<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    use HasFactory;

    protected $table = 'angsurans';

    protected $fillable = [
        'pinjaman_id',
        'anggota_id',
        'angsuran_ke',
        'tanggal_bayar',
        'nominal',
        'denda',
        'keterangan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
            'nominal' => 'decimal:2',
            'denda' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }
}
