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
        Schema::create('stock_transfer_records', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_id');
            $table->string('sourceGarage');
            $table->string('receiverGarage');
            $table->string('product_category');
            $table->string('product_code');
            $table->string('product_name');
            $table->string('product_serial');
            $table->string('product_brand');
            $table->string('product_unit');
            $table->string('product_details');
            $table->string('product_outqty');
            $table->string('date_transfer');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfer_records');
    }
};
