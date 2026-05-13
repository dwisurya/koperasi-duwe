<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggotas';

    protected $fillable = [
        'kode',
        'nama',
        'nik',
        'no_kk',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'email',
        'no_hp',
        'tanggal_daftar',
        'tanggal_keluar',
        'ayah',
        'ibu',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_daftar' => 'date',
            'tanggal_keluar' => 'date',
        ];
    }

    public function isAktif(): bool
    {
        return $this->tanggal_keluar === null;
    }

    protected static function booted(): void
    {
        static::creating(function (Anggota $anggota) {
            if (! $anggota->kode) {
                $last = static::query()->latest('id')->first();
                $next = $last ? $last->id + 1 : 1;
                $tgl = $anggota->tanggal_daftar ? \Carbon\Carbon::parse($anggota->tanggal_daftar) : now();
                $bulan = match ((int) $tgl->format('n')) {
                    1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
                    7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
                };
                $anggota->kode = str_pad($next, 4, '0', STR_PAD_LEFT).'/Duwe/'.$bulan.'/'.$tgl->format('y');
            }
        });
    }

    public function simpanan()
    {
        return $this->hasMany(Simpanan::class);
    }

    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class);
    }
}
