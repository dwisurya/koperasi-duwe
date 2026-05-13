<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shu extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode_id',
        'total_shu',
        'total_aktiva',
        'total_passiva',
        'is_distributed',
        'distributed_at',
    ];

    protected function casts(): array
    {
        return [
            'total_shu' => 'decimal:2',
            'total_aktiva' => 'decimal:2',
            'total_passiva' => 'decimal:2',
            'is_distributed' => 'boolean',
            'distributed_at' => 'datetime',
        ];
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function details()
    {
        return $this->hasMany(ShuDetail::class);
    }

    public static function distributionSchema(): array
    {
        return PersentaseShu::where('is_active', true)
            ->orderBy('urutan')
            ->get(['dana', 'persentase'])
            ->toArray();
    }
}
