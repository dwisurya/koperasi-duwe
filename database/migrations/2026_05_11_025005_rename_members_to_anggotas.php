<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('members', 'anggotas');
    }

    public function down(): void
    {
        Schema::rename('anggotas', 'members');
    }
};
