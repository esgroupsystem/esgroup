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
        Schema::create('purchase_request_orders', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->string('garage_name');
            $table->string('product_category');
            $table->string('product_name');
            $table->string('product_code')->nullable();
            $table->string('product_serial')->nullable();
            $table->string('product_brand')->nullable();
            $table->string('product_unit');
            $table->text('product_details')->nullable();
            $table->date('request_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase__request__orders');
    }
};
