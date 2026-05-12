<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bunga_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('bunga', 5, 2);
            $table->string('jenis')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bunga_pinjaman');
    }
};
