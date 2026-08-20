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
        Schema::create('karigars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('dob')->nullable();
            $table->string('aadhar_no')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('aadhar_front')->nullable();
            $table->string('aadhar_back')->nullable();
            $table->string('photo')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_no')->nullable();
            
            $table->foreignId('machine_1_id')->nullable()->constrained('machines')->nullOnDelete();
            $table->decimal('machine_1_top_rs', 8, 2)->nullable();
            $table->decimal('machine_1_dup_rs', 8, 2)->nullable();
            
            $table->foreignId('machine_2_id')->nullable()->constrained('machines')->nullOnDelete();
            $table->decimal('machine_2_top_rs', 8, 2)->nullable();
            $table->decimal('machine_2_dup_rs', 8, 2)->nullable();
            
            $table->foreignId('machine_3_id')->nullable()->constrained('machines')->nullOnDelete();
            $table->decimal('machine_3_top_rs', 8, 2)->nullable();
            $table->decimal('machine_3_dup_rs', 8, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karigars');
    }
};
