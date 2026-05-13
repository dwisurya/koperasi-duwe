<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('titip_dana', 'status')) {
            Schema::table('titip_dana', function (Blueprint $table) {
                $table->enum('status', ['belum_diketahui', 'sudah_diketahui'])->default('belum_diketahui');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('titip_dana', 'status')) {
            Schema::table('titip_dana', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
