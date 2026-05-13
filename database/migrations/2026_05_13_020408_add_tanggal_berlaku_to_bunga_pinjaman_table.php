<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('bunga_pinjaman', 'tanggal_berlaku')) {
            Schema::table('bunga_pinjaman', function (Blueprint $table) {
                $table->date('tanggal_berlaku')->nullable()->after('bunga');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('bunga_pinjaman', 'tanggal_berlaku')) {
            Schema::table('bunga_pinjaman', function (Blueprint $table) {
                $table->dropColumn('tanggal_berlaku');
            });
        }
    }
};
