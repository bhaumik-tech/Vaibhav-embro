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
        Schema::table('inter_exchanges', function (Blueprint $table) {
            $table->dropForeign(['firm_aapnar_id']);
            $table->dropForeign(['firm_lenar_id']);
            $table->dropColumn(['firm_aapnar_id', 'firm_lenar_id']);
            
            $table->foreignId('user_aapnar_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_lenar_id')->nullable()->constrained('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inter_exchanges', function (Blueprint $table) {
            $table->dropForeign(['user_aapnar_id']);
            $table->dropForeign(['user_lenar_id']);
            $table->dropColumn(['user_aapnar_id', 'user_lenar_id']);
            
            $table->foreignId('firm_aapnar_id')->nullable()->constrained('firms')->cascadeOnDelete();
            $table->foreignId('firm_lenar_id')->nullable()->constrained('firms')->cascadeOnDelete();
        });
    }
};
