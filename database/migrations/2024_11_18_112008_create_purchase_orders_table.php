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
            $table->string('request_id');
            $table->string('po_number')->nullable();
            $table->string('garage_name');
            $table->string('product_code');
            $table->string('product_serial');
            $table->string('product_name');
            $table->string('product_category');
            $table->string('product_brand');
            $table->string('product_unit');
            $table->string('product_supplier')->nullable();
            $table->string('product_details');
            $table->string('payment_terms');
            $table->string('remarks');
            $table->string('request_date');
            $table->string('purchase_date')->nullable();
            $table->string('status');
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
