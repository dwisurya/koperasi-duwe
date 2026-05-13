<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersentaseShu extends Model
{
    use HasFactory;

    protected $table = 'persentase_shu';

    protected $fillable = [
        'dana',
        'persentase',
        'keterangan',
        'urutan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'persentase' => 'decimal:2',
            'urutan' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
