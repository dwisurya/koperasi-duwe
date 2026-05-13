<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunKeuangan extends Model
{
    use HasFactory;

    protected $table = 'akun_keuangan';

    protected $fillable = [
        'kode',
        'nama',
        'kategori_aktiva_id',
        'kategori_passiva_id',
        'keterangan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function kategoriAktiva()
    {
        return $this->belongsTo(KategoriAktiva::class);
    }

    public function kategoriPassiva()
    {
        return $this->belongsTo(KategoriPassiva::class);
    }

    public function getKategoriLabelAttribute(): string
    {
        if ($this->kategori_aktiva_id) {
            return 'Aktiva';
        }
        if ($this->kategori_passiva_id) {
            return 'Passiva';
        }

        return '-';
    }

    public function getKategoriNamaAttribute(): string
    {
        if ($this->kategori_aktiva_id && $this->relationLoaded('kategoriAktiva') && $this->kategoriAktiva) {
            return $this->kategoriAktiva->nama;
        }
        if ($this->kategori_passiva_id && $this->relationLoaded('kategoriPassiva') && $this->kategoriPassiva) {
            return $this->kategoriPassiva->nama;
        }

        return '-';
    }
}
