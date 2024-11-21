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
        Schema::create('purchase_order_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('purchase_order_id');
        $table->string('request_id');
        $table->string('product_code');
        $table->string('product_name');
        $table->integer('qty');
        $table->decimal('amount', 10, 2);
        $table->timestamps();

        $table->foreign('purchase_order_id')
              ->references('id')
              ->on('purchase_orders')
              ->onDelete('cascade');
              
        $table->foreign('request_id')
              ->references('request_id')
              ->on('purchase_orders')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
