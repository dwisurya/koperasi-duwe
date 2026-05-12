<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjaman';

    protected $fillable = [
        'anggota_id',
        'tanggal_pengajuan',
        'nominal',
        'bunga_pinjaman_id',
        'bunga_persen',
        'tenor',
        'jatuh_tempo',
        'keterangan',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
            'bunga_persen' => 'decimal:2',
            'tenor' => 'integer',
            'tanggal_pengajuan' => 'date',
            'jatuh_tempo' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $pinjaman) {
            if (! $pinjaman->status) {
                $pinjaman->status = 'diajukan';
            }
            if ($pinjaman->tanggal_pengajuan && $pinjaman->tenor) {
                $pinjaman->jatuh_tempo = $pinjaman->tanggal_pengajuan->addMonths($pinjaman->tenor);
            }
        });

        static::updating(function (self $pinjaman) {
            if ($pinjaman->isDirty(['tanggal_pengajuan', 'tenor']) && $pinjaman->tanggal_pengajuan && $pinjaman->tenor) {
                $pinjaman->jatuh_tempo = $pinjaman->tanggal_pengajuan->addMonths($pinjaman->tenor);
            }
        });
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function bungaPinjaman()
    {
        return $this->belongsTo(BungaPinjaman::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'diajukan');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'diajukan' => 'Diajukan',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'aktif' => 'Aktif',
            'lunas' => 'Lunas',
            'macet' => 'Macet',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'diajukan' => 'warning',
            'disetujui' => 'info',
            'ditolak' => 'dark',
            'aktif' => 'primary',
            'lunas' => 'success',
            'macet' => 'danger',
            default => 'secondary',
        };
    }
}
