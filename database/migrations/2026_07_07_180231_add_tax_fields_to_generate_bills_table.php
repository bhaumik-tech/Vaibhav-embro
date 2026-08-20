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
        Schema::table('generate_bills', function (Blueprint $table) {
            $table->decimal('vatav_percent', 5, 2)->default(5.00)->after('gst');
            $table->decimal('sgst_percent', 5, 2)->default(2.50)->after('vatav_percent');
            $table->decimal('cgst_percent', 5, 2)->default(2.50)->after('sgst_percent');
            $table->decimal('tds_percent', 5, 2)->default(1.00)->after('cgst_percent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generate_bills', function (Blueprint $table) {
            //
        });
    }
};
