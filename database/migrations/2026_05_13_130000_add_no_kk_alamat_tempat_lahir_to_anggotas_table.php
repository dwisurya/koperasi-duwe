<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->string('no_kk', 30)->nullable()->after('nik');
            $table->text('alamat')->nullable()->after('no_kk');
            $table->string('tempat_lahir', 100)->nullable()->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropColumn(['no_kk', 'alamat', 'tempat_lahir']);
        });
    }
};
