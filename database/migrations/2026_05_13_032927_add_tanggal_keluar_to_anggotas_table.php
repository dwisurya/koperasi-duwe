<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('anggotas', 'tanggal_keluar')) {
            Schema::table('anggotas', function (Blueprint $table) {
                $table->date('tanggal_keluar')->nullable()->after('tanggal_daftar');
            });
        }
    }

    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropColumn('tanggal_keluar');
        });
    }
};
