<?php

use App\Models\Anggota;
use App\Models\Simpanan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (Anggota::where('saldo_awal', '>', 0)->cursor() as $anggota) {
            Simpanan::firstOrCreate(
                ['anggota_id' => $anggota->id, 'jenis' => 'pokok'],
                [
                    'nominal' => $anggota->saldo_awal,
                    'keterangan' => 'Saldo awal dikonversi ke Simpanan Pokok',
                    'is_active' => true,
                ]
            );
        }

        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropColumn('saldo_awal');
        });
    }

    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->decimal('saldo_awal', 15, 2)->default(0)->after('ibu');
        });
    }
};
