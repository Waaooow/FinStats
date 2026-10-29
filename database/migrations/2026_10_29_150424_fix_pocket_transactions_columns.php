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
        Schema::table('pocket_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('pocket_transactions', 'pocket_id')) {
                $table->foreignId('pocket_id')->constrained()->onDelete('cascade')->after('id');
            }
            if (! Schema::hasColumn('pocket_transactions', 'type')) {
                $table->enum('type', ['top_up', 'withdraw', 'transfer_in', 'transfer_out'])->after('pocket_id');
            }
            if (! Schema::hasColumn('pocket_transactions', 'amount')) {
                $table->decimal('amount', 15, 2)->after('type');
            }
            if (! Schema::hasColumn('pocket_transactions', 'description')) {
                $table->string('description')->nullable()->after('amount');
            }
            if (! Schema::hasColumn('pocket_transactions', 'transaction_date')) {
                $table->date('transaction_date')->after('description');
            }
            if (! Schema::hasColumn('pocket_transactions', 'related_pocket_id')) {
                $table->foreignId('related_pocket_id')->nullable()->constrained('pockets')->onDelete('set null')->after('transaction_date');
            }
            if (! Schema::hasColumn('pocket_transactions', 'related_group_id')) {
                $table->foreignId('related_group_id')->nullable()->constrained('groups')->onDelete('set null')->after('related_pocket_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pocket_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('pocket_transactions', 'related_group_id')) {
                $table->dropForeign(['related_group_id']);
                $table->dropColumn('related_group_id');
            }
            if (Schema::hasColumn('pocket_transactions', 'related_pocket_id')) {
                $table->dropForeign(['related_pocket_id']);
                $table->dropColumn('related_pocket_id');
            }
            if (Schema::hasColumn('pocket_transactions', 'transaction_date')) {
                $table->dropColumn('transaction_date');
            }
            if (Schema::hasColumn('pocket_transactions', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('pocket_transactions', 'amount')) {
                $table->dropColumn('amount');
            }
            if (Schema::hasColumn('pocket_transactions', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('pocket_transactions', 'pocket_id')) {
                $table->dropForeign(['pocket_id']);
                $table->dropColumn('pocket_id');
            }
        });
    }
};
