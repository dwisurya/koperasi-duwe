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
        'periode_id',
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

    protected static function booted(): void
    {
        static::creating(function (self $simpanan) {
            if (! $simpanan->periode_id) {
                $simpanan->periode_id = Periode::getActiveId();
            }
        });

        static::created(function (self $simpanan) {
            Kas::create([
                'tanggal' => now(),
                'jenis' => 'masuk',
                'kategori' => $simpanan->jenis_label,
                'nominal' => $simpanan->nominal,
                'keterangan' => 'Setoran '.$simpanan->jenis_label.' a.n. '.($simpanan->anggota?->nama ?? '-'),
                'periode_id' => $simpanan->periode_id,
            ]);
        });
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function scopePeriodeAktif($query)
    {
        return $query->where('periode_id', Periode::getActiveId());
    }

    public function getJenisLabelAttribute(): string
    {
        return static::jenisLabel($this->jenis);
    }

    public static function jenisLabel(string $jenis): string
    {
        return match ($jenis) {
            'pokok' => 'Simpanan Pokok',
            'wajib' => 'Simpanan Wajib',
            'penyertaan' => 'Tabungan Penyertaan',
            'bagi_hasil' => 'Bagi Hasil',
            default => $jenis,
        };
    }
}
