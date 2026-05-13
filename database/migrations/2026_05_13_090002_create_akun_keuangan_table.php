<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akun_keuangan', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->foreignId('kategori_aktiva_id')->nullable()->constrained('kategori_aktiva')->nullOnDelete();
            $table->foreignId('kategori_passiva_id')->nullable()->constrained('kategori_passiva')->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akun_keuangan');
    }
};
