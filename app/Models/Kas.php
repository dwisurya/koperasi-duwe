<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasFactory;

    protected $table = 'kas';

    protected $fillable = [
        'tanggal',
        'jenis',
        'kategori',
        'nominal',
        'keterangan',
        'periode_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'nominal' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $kas) {
            if (! $kas->periode_id) {
                $kas->periode_id = Periode::getActiveId();
            }
        });
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
        return $this->jenis === 'masuk' ? 'Pemasukan' : 'Pengeluaran';
    }

    public function getNominalFormatAttribute(): string
    {
        return 'Rp '.number_format($this->nominal, 0, ',', '.');
    }
}
