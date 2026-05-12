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
        'jenis',
        'keterangan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'bunga' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
