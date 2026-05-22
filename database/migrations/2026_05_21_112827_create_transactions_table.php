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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Mengaitkan transaksi dengan user yang sedang login
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Pilihan tipe transaksi
            $table->enum('type', ['income', 'outcome']);
            // Nominal uang (15 digit angka, 2 digit di belakang koma)
            $table->decimal('amount', 15, 2);
            // Keterangan input
            $table->string('description');
            // Tanggal transaksi
            $table->date('date');
            $table->timestamps(); // Otomatis membuat created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
