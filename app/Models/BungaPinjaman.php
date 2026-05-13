<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BungaPinjaman extends Model
{
    use HasFactory;

    protected $table = 'bunga_pinjaman';

    protected $fillable = [
        'nama',
        'bunga',
        'tanggal_berlaku',
        'jenis',
        'keterangan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'bunga' => 'decimal:2',
            'tanggal_berlaku' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public static function getRateByDate(string $date): ?self
    {
        return static::aktif()
            ->where('tanggal_berlaku', '<=', $date)
            ->orderByDesc('tanggal_berlaku')
            ->first();
    }

    public static function currentActive(): ?self
    {
        return static::aktif()
            ->orderByDesc('tanggal_berlaku')
            ->first();
    }
}
