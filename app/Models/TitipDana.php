<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitipDana extends Model
{
    use HasFactory;

    protected $table = 'titip_dana';

    protected $fillable = [
        'nama_penitip',
        'tanggal',
        'jenis',
        'status',
        'nominal',
        'keterangan',
        'periode_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'nominal' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'belum_diketahui' => 'Belum Diketahui',
            'sudah_diketahui' => 'Sudah Diketahui',
            default => $this->status,
        };
    }

    protected static function booted(): void
    {
        static::creating(function (self $titipDana) {
            if (! $titipDana->periode_id) {
                $titipDana->periode_id = Periode::getActiveId();
            }
        });
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
