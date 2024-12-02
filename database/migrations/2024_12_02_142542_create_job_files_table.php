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
        Schema::create('job_files', function (Blueprint $table) {
            $table->id();
            $table->string('job_id')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_remarks')->nullable();
            $table->string('file_notes')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_files');
    }
};
