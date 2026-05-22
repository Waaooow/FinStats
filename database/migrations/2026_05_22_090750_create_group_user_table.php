<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint; // <-- Memastikan class Blueprint diimport
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // KOREKSI: Menggunakan Blueprint $table
        Schema::create('group_user', function (Blueprint $table) {
            $table->id();
            // foreignId otomatis membuat kolom link antar ID tabel dan mengunci integritas datanya
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_user');
    }
};
