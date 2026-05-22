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
        Schema::table('transactions', function (Blueprint $table) {
            // nullable() dipasang agar jika kolom dikosongkan, Laravel tahu itu transaksi personal
            $table->foreignId('group_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');
        });
    }
};
