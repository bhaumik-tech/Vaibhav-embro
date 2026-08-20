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
        Schema::table('generate_chalans', function (Blueprint $table) {
            $table->string('party_ch')->nullable();
            $table->string('gst')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_detail')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generate_chalans', function (Blueprint $table) {
            //
        });
    }
};
