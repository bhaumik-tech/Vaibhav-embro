<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('output_chalans')) {
            Schema::create('output_chalans', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('output_chalan_items')) {
            Schema::create('output_chalan_items', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }

        Schema::table('output_chalans', function (Blueprint $table) {
            if (!Schema::hasColumn('output_chalans', 'firm_id')) {
                $table->foreignId('firm_id')->nullable()->constrained('firms')->nullOnDelete();
            }
            if (!Schema::hasColumn('output_chalans', 'party_id')) {
                $table->foreignId('party_id')->nullable()->constrained('parties')->nullOnDelete();
            }
            if (!Schema::hasColumn('output_chalans', 'chalan_no')) {
                $table->string('chalan_no')->nullable();
            }
            if (!Schema::hasColumn('output_chalans', 'date')) {
                $table->date('date')->nullable();
            }
            if (!Schema::hasColumn('output_chalans', 'party_chalan_no')) {
                $table->string('party_chalan_no')->nullable();
            }
            if (!Schema::hasColumn('output_chalans', 'total_pcs')) {
                $table->integer('total_pcs')->nullable();
            }
            if (!Schema::hasColumn('output_chalans', 'total_amount')) {
                $table->decimal('total_amount', 12, 2)->nullable();
            }
            if (!Schema::hasColumn('output_chalans', 'gst')) {
                $table->string('gst')->nullable();
            }
            if (!Schema::hasColumn('output_chalans', 'payment_date')) {
                $table->date('payment_date')->nullable();
            }
            if (!Schema::hasColumn('output_chalans', 'payment_detail')) {
                $table->string('payment_detail')->nullable();
            }
            if (!Schema::hasColumn('output_chalans', 'is_done')) {
                $table->boolean('is_done')->default(false);
            }
        });

        Schema::table('output_chalan_items', function (Blueprint $table) {
            if (!Schema::hasColumn('output_chalan_items', 'output_chalan_id')) {
                $table->foreignId('output_chalan_id')->nullable()->constrained('output_chalans')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('output_chalan_items', 'ch_no')) {
                $table->string('ch_no')->nullable();
            }
            if (!Schema::hasColumn('output_chalan_items', 'bundle')) {
                $table->string('bundle')->nullable();
            }
            if (!Schema::hasColumn('output_chalan_items', 'code')) {
                $table->string('code')->nullable();
            }
            if (!Schema::hasColumn('output_chalan_items', 'pcs')) {
                $table->integer('pcs')->nullable();
            }
            if (!Schema::hasColumn('output_chalan_items', 'rate')) {
                $table->decimal('rate', 12, 2)->nullable();
            }
            if (!Schema::hasColumn('output_chalan_items', 'amount')) {
                $table->decimal('amount', 12, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('output_chalan_items')) {
            Schema::table('output_chalan_items', function (Blueprint $table) {
                $table->dropColumn(['output_chalan_id', 'ch_no', 'bundle', 'code', 'pcs', 'rate', 'amount']);
            });
        }

        if (Schema::hasTable('output_chalans')) {
            Schema::table('output_chalans', function (Blueprint $table) {
                $table->dropColumn(['firm_id', 'party_id', 'chalan_no', 'date', 'party_chalan_no', 'total_pcs', 'total_amount', 'gst', 'payment_date', 'payment_detail', 'is_done']);
            });
        }
    }
};
