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
        Schema::table('thread_box_items', function (Blueprint $table) {
            $table->string('type_of_box')->nullable()->change();
            $table->string('box_cone')->nullable()->change();
            $table->decimal('quantity', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thread_box_items', function (Blueprint $table) {
            $table->string('type_of_box')->nullable(false)->change();
            $table->string('box_cone')->nullable(false)->change();
            $table->decimal('quantity', 10, 2)->nullable(false)->change();
        });
    }
};
