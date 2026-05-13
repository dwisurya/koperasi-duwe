<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_shu', 18, 2);
            $table->decimal('total_aktiva', 18, 2);
            $table->decimal('total_passiva', 18, 2);
            $table->boolean('is_distributed')->default(false);
            $table->timestamp('distributed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('shu_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shu_id')->constrained()->cascadeOnDelete();
            $table->string('dana');
            $table->decimal('persentase', 5, 2);
            $table->decimal('nominal', 18, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shu_details');
        Schema::dropIfExists('shus');
    }
};
