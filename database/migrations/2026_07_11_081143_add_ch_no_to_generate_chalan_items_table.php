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
        Schema::table('generate_chalan_items', function (Blueprint $table) {
            $table->string('ch_no')->nullable()->after('generate_chalan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generate_chalan_items', function (Blueprint $table) {
            $table->dropColumn('ch_no');
        });
    }
};
