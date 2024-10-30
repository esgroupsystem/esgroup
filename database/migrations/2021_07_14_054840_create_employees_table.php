<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('company')->nullable();
            $table->unsignedBigInteger('department_id')->nullable(); 
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->string('garage')->nullable();
            $table->string('date_hired')->nullable();
            $table->string('end_date')->nullable();
            $table->string('status')->nullable();
            $table->string('submited_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        
            // Adding foreign key constraints
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
