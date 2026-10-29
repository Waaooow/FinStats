<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pockets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nama pocket bebas ditentukan user (ex: "Tabungan Liburan", "Saku Harian")
            $table->decimal('target', 15, 2)->nullable(); // Target opsional, tidak wajib
            $table->decimal('balance', 15, 2)->default(0); // Saldo terkini pocket
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pockets');
    }
};
