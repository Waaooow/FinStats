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
        Schema::create('pocket_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pocket_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['top_up', 'withdraw', 'transfer_in', 'transfer_out']);
            $table->decimal('amount', 15, 2);
            $table->string('description')->nullable();
            $table->date('transaction_date');
            $table->foreignId('related_pocket_id')->nullable()->constrained('pockets')->onDelete('set null');
            $table->foreignId('related_group_id')->nullable()->constrained('groups')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pocket_transactions');
    }
};
