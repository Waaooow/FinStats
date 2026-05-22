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
        // KOREKSI: Menggunakan Blueprint $table dan kolom standar (name, code)
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Nama kelompok tabungan (ex: "Kas Angkatan")
            $table->string('code')->unique(); // Kode unik untuk token masuk group (ex: KAS-771)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
