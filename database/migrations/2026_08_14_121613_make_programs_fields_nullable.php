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
        Schema::table('programs', function (Blueprint $table) {
            $table->unsignedBigInteger('party_id')->nullable()->change();
            $table->decimal('mtr', 10, 2)->nullable()->default(null)->change();
            $table->integer('pcs')->nullable()->default(null)->change();
            $table->decimal('rs', 10, 2)->nullable()->default(null)->change();
            $table->decimal('work_percent', 5, 2)->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->unsignedBigInteger('party_id')->nullable(false)->change();
            $table->decimal('mtr', 10, 2)->default(0)->change();
            $table->integer('pcs')->default(0)->change();
            $table->decimal('rs', 10, 2)->default(0)->change();
            $table->decimal('work_percent', 5, 2)->default(0)->change();
        });
    }
};
