<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    use HasFactory;

    protected $table = 'simpanan';

    protected $fillable = [
        'anggota_id',
        'jenis',
        'nominal',
        'keterangan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {
            'pokok' => 'Simpanan Pokok',
            'wajib' => 'Simpanan Wajib',
            'sukarela' => 'Simpanan Sukarela',
            'bagi_hasil' => 'Bagi Hasil',
            default => $this->jenis,
        };
    }
}
