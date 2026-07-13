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
        Schema::table('dh_cutting_people', function (Blueprint $table) {
            $table->date('dob')->nullable()->after('aadhar_card_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dh_cutting_people', function (Blueprint $table) {
            $table->dropColumn('dob');
        });
    }
};
