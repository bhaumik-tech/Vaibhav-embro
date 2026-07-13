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
        Schema::create('dh_cutting_people', function (Blueprint $table) {
            $table->id();
            $table->string('person_name');
            $table->string('lj_type')->nullable(); // L/J
            $table->string('person_code')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('aadhar_card_no')->nullable();
            $table->string('second_mobile_no')->nullable();
            $table->text('full_address')->nullable();
            $table->text('remark_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dh_cutting_people');
    }
};
