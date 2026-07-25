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
        Schema::table('input_chalans', function (Blueprint $table) {
            $table->dropUnique('input_chalans_chalan_no_unique');
            $table->unique(['party_id', 'firm_id', 'chalan_no'], 'input_chalans_composite_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('input_chalans', function (Blueprint $table) {
            $table->dropUnique('input_chalans_composite_unique');
            $table->unique('chalan_no');
        });
    }
};
