<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggotas')->cascadeOnDelete();
            $table->decimal('nominal', 12, 2);
            $table->foreignId('bunga_pinjaman_id')->nullable()->constrained('bunga_pinjaman');
            $table->decimal('bunga_persen', 5, 2);
            $table->integer('tenor');
            $table->date('jatuh_tempo');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak', 'aktif', 'lunas', 'macet'])->default('diajukan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
