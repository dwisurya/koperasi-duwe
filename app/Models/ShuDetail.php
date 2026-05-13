<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShuDetail extends Model
{
    use HasFactory;

    protected $table = 'shu_details';

    protected $fillable = [
        'shu_id',
        'dana',
        'persentase',
        'nominal',
    ];

    protected function casts(): array
    {
        return [
            'persentase' => 'decimal:2',
            'nominal' => 'decimal:2',
        ];
    }

    public function shu()
    {
        return $this->belongsTo(Shu::class);
    }
}
