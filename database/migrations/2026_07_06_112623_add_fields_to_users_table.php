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
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('primary_firm_name')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('post')->nullable();
            $table->string('second_mobile_no')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('permission')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->dropColumn([
                'primary_firm_name',
                'mobile_no',
                'post',
                'second_mobile_no',
                'username',
                'permission'
            ]);
        });
    }
};
