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
        Schema::create('joborders', function (Blueprint $table) {
            $table->id();
            $table->string('job_name')->nullable();
            $table->string('job_type')->nullable();
            $table->string('job_datestart')->nullable();
            $table->string('job_time_start')->nullable();
            $table->string('job_time_end')->nullable();
            $table->string('job_sitNumber')->nullable();
            $table->string('job_remarks')->nullable();
            $table->string('job_status')->nullable();
            $table->string('job_assign_person')->nullable();
            $table->string('job_date_filled')->nullable();
            $table->string('job_creator')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joborders');
    }
};
