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
        'tanggal_lahir',
        'jenis_kelamin',
        'email',
        'no_hp',
        'tanggal_daftar',
        'ayah',
        'ibu',
        'saldo_awal',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_daftar' => 'date',
            'saldo_awal' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Anggota $anggota) {
            if (! $anggota->kode) {
                $last = static::query()->latest('id')->first();
                $next = $last ? $last->id + 1 : 1;
                $anggota->kode = 'AG-'.str_pad($next, 5, '0', STR_PAD_LEFT);
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
