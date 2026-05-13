<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persentase_shu', function (Blueprint $table) {
            $table->id();
            $table->string('dana');
            $table->decimal('persentase', 5, 2);
            $table->text('keterangan')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persentase_shu');
    }
};
