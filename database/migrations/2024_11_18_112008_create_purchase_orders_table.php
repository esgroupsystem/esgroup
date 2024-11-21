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
            $table->string('garage_name')->nullable();
            $table->string('product_code')->nullable();
            $table->string('product_serial')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_category')->nullable();
            $table->string('product_brand')->nullable();
            $table->string('product_unit')->nullable();
            $table->string('product_supplier')->nullable();
            $table->string('product_details')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('remarks')->nullable();
            $table->string('request_date')->nullable();
            $table->string('purchase_date')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->index('request_id');
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
