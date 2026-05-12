<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('simpanan', function (Blueprint $table) {
            $table->foreignId('periode_id')->nullable()->constrained('periodes')->nullOnDelete()->after('anggota_id');
        });

        Schema::table('pinjaman', function (Blueprint $table) {
            $table->foreignId('periode_id')->nullable()->constrained('periodes')->nullOnDelete()->after('anggota_id');
        });
    }

    public function down(): void
    {
        Schema::table('simpanan', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });

        Schema::table('pinjaman', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });
    }
};
