<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_schedules', function (Blueprint $table) {
            $table->time('time_in')->nullable()->after('end_time');
            $table->time('time_out')->nullable()->after('time_in');
            $table->decimal('remit', 10, 2)->nullable()->after('time_out');
            $table->decimal('diesel', 10, 2)->nullable()->after('remit');
        });
    }

    public function down(): void
    {
        Schema::table('employee_schedules', function (Blueprint $table) {
            $table->dropColumn(['time_in', 'time_out', 'remit', 'diesel']);
        });
    }
};
