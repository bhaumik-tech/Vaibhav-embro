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
            $table->date('payment_date')->nullable();
            $table->string('payment_detail')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generate_bills', function (Blueprint $table) {
            $table->dropColumn(['payment_date', 'payment_detail']);
        });
    }
};
