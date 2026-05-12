<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropColumn(['address', 'birth_date', 'is_active']);
        });

        Schema::table('anggotas', function (Blueprint $table) {
            $table->renameColumn('name', 'nama');
            $table->renameColumn('gender', 'jenis_kelamin');
            $table->renameColumn('phone', 'no_hp');
        });

        Schema::table('anggotas', function (Blueprint $table) {
            $table->string('kode')->unique()->after('id');
            $table->string('nik', 20)->nullable()->after('nama');
            $table->date('tanggal_daftar')->nullable()->after('no_hp');
            $table->string('ayah', 100)->nullable()->after('tanggal_daftar');
            $table->string('ibu', 100)->nullable()->after('ayah');
        });
    }

    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropColumn(['kode', 'nik', 'tanggal_daftar', 'ayah', 'ibu']);
        });

        Schema::table('anggotas', function (Blueprint $table) {
            $table->renameColumn('nama', 'name');
            $table->renameColumn('jenis_kelamin', 'gender');
            $table->renameColumn('no_hp', 'phone');
        });

        Schema::table('anggotas', function (Blueprint $table) {
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }
};
