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
        Schema::table('parties', function (Blueprint $table) {
            $table->decimal('vatav', 5, 2)->nullable()->after('address');
            $table->decimal('sgst', 5, 2)->nullable()->after('vatav');
            $table->decimal('cgst', 5, 2)->nullable()->after('sgst');
            $table->decimal('tds', 5, 2)->nullable()->after('cgst');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parties', function (Blueprint $table) {
            $table->dropColumn(['vatav', 'sgst', 'cgst', 'tds']);
        });
    }
};
