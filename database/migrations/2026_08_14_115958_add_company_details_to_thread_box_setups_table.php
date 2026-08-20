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
        Schema::table('thread_box_setups', function (Blueprint $table) {
            $table->string('second_name')->nullable()->after('company_name');
            $table->string('gst_number')->nullable()->after('second_name');
            $table->text('address')->nullable()->after('gst_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thread_box_setups', function (Blueprint $table) {
            $table->dropColumn(['second_name', 'gst_number', 'address']);
        });
    }
};
