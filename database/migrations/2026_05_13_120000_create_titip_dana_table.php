<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titip_dana', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penitip');
            $table->date('tanggal');
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->decimal('nominal', 18, 2);
            $table->text('keterangan')->nullable();
            $table->foreignId('periode_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titip_dana');
    }
};
