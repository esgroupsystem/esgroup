<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('biometric_logs', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('employee_name')->nullable();
            $table->dateTime('log_time');
            $table->string('status')->nullable(); // e.g. IN, OUT
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biometric_logs');
    }
};
