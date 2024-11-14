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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_no')->nullable();
            $table->string('product_id')->unique();
            $table->string('supplier_id')->unique();
            $table->string('garage_id')->unique();
            $table->string('requestor_id')->unique();
            $table->string('isapproved')->nullable();
            $table->integer('total_amount')->nullable();
            $table->string('request_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
