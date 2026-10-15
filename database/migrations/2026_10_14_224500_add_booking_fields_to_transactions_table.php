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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('amount');
            $table->date('visit_date')->nullable()->after('phone');
            $table->integer('participants')->default(1)->after('visit_date');
            $table->text('notes')->nullable()->after('participants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['phone', 'visit_date', 'participants', 'notes']);
        });
    }
};
